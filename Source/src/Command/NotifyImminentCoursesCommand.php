<?php /** @noinspection PhpInternalEntityUsedInspection */

namespace App\Command;

use App\Command\Interface\NotificationInterface;
use App\Repository\SingleEventRepository;
use App\Service\PushNotificationService;
use Exception;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Pimcore\Console\AbstractCommand;
use Pimcore\Log\Simple;
use Pimcore\Model\DataObject\SingleEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyImminentCoursesCommand extends AbstractCommand implements NotificationInterface
{

    private PushNotificationService $notificationService;
    private SingleEventRepository $eventRepository;

    public function __construct(PushNotificationService $notificationService, SingleEventRepository $eventRepository, string $name = null)
    {
        $this->notificationService = $notificationService;
        $this->eventRepository = $eventRepository;
        parent::__construct($name);
    }

    public function configure()
    {
        $this
            ->setName('hanse:notify:imminent')
            ->setDescription('Checks and notifies course users 15 minutes prior course event start, cancels events without streaming link')
            ->setHelp('This command checks for course events, that are starting in 15 minutes (+5 minutes cronjob interval)
and sends a Firebase Cloud Message to the corresponding topic. Subscribed app users will then receive a notification. Also checks, if the event has a streaming link.
If a link is missing, the event will be cancelled and the subscribed users notified about the cancellation.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if($events = $this->eventRepository->getUnnotifiedEventsStartingIn15Minutes()) {

            Simple::log('pushnotifications', 'Imminent: Found '.count($events).' notifiable, imminent events.');

            /** @var SingleEvent $event */
            foreach ($events as $event) {

                if($event->getStreamLink()) {
                    if($event->getBookings()) {
                        Simple::log('pushnotifications', 'Imminent: Event #'.$event->getId().' has Bookings.');
                        $this->logAndNotifySubscribers($event);
                    }
                } else {
                    $this->cancelEventAndNotifySubscribers($event);
                }
                // set notified field also when no notification was sent because of missing bookings,
                // so this event won't be listed as notifiable again.
                $event->setProperty(self::NOTIFIED_START, 'bool', true);
                $event->save();
            }
        }

        return 0;
    }


    private function cancelEventAndNotifySubscribers(SingleEvent $event): void
    {
        Simple::log(
            'pushnotifications',
            'Break: No streaming link for event #' . $event->getId() . ', cancelling event.'
        );
        // Notifiy users if the event has bookings
        if($event->getBookings()) {
            try {
                if ($this->notificationService->sendMessage($event->getId(), PushNotificationService::MESSAGE_TYPE_CANCELLED)) {
                    Simple::log('pushnotifications', 'Cancellation: Sent notification for event #' . $event->getId());
                }
            } catch (Exception $e) {
                Simple::log('pushnotifications', sprintf('Exception: %s', $e->getMessage()));
            } catch (MessagingException $e) {
                Simple::log('pushnotifications', sprintf('MessagingException: %s', $e->getMessage()));
            } catch (FirebaseException $e) {
                Simple::log('pushnotifications', sprintf('FirebaseException: %s', $e->getMessage()));
            }
        }

        $event->setCancelled(true);
        $event->setProperty(self::NOTIFIED_CANCELLED, 'bool', true);
    }


    private function logAndNotifySubscribers(SingleEvent $event): void
    {
        try {
            if ($this->notificationService->sendMessage($event->getId(), PushNotificationService::MESSAGE_TYPE_START)) {
                Simple::log('pushnotifications', 'Imminent: Sent notification for event #' . $event->getId());
            }
        } catch (Exception $e) {
            Simple::log('pushnotifications', sprintf('Exception: %s', $e->getMessage()));
        } catch (MessagingException $e) {
            Simple::log('pushnotifications', sprintf('MessagingException: %s', $e->getMessage()));
        } catch (FirebaseException $e) {
            Simple::log('pushnotifications', sprintf('FirebaseException: %s', $e->getMessage()));
        }
    }

}
