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

class NotifyCancelledCoursesCommand extends AbstractCommand implements NotificationInterface
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
            ->setName('hanse:notify:cancelled')
            ->setDescription('Checks and notifies course users if an event was cancelled')
            ->setHelp('This command checks for course events that were cancelled in the last 5 minutes (cronjob
interval) by the course owner or a Hansefit employee and sends a Firebase Cloud Message to the corresponding topic.
Subscribed app users will then receive a notification.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if($events = $this->eventRepository->getUnnotifiedCancelledEventsOfTheLast15Minutes()) {
            Simple::log('pushnotifications', sprintf('Cancellation: Found %d notifiable, cancelled events.', count($events)) );
            foreach ($events as $event) {
                $this->logAndNotifySubscribers($event);

                $event->setProperty(self::NOTIFIED_CANCELLED, 'bool', true);
                $event->save();
            }
        }

        return 0;
    }

    private function logAndNotifySubscribers(SingleEvent $event): void
    {
        Simple::log('pushnotifications', sprintf('Cancellation: Event #%s has Bookings.', $event->getId()) );

        try {
            if ($this->notificationService->sendMessage($event->getId(),PushNotificationService::MESSAGE_TYPE_CANCELLED)) {
                Simple::log('pushnotifications',sprintf('Cancellation: Sent notification for event #%s', $event->getId()));
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
