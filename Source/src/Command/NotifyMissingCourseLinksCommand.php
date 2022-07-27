<?php /** @noinspection PhpInternalEntityUsedInspection */

namespace App\Command;

use App\Command\Interface\NotificationInterface;
use App\Repository\SingleEventRepository;
use App\Service\EmailNotificationService;
use Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Log\Simple;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyMissingCourseLinksCommand extends AbstractCommand implements NotificationInterface
{
    private SingleEventRepository $singleEventRepository;
    private EmailNotificationService $notificationService;

    public function __construct(SingleEventRepository$singleEventRepository, EmailNotificationService $notificationService, string $name = null)
    {
        parent::__construct($name);
        $this->singleEventRepository = $singleEventRepository;
        $this->notificationService = $notificationService;
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:notify:missinglink')
            ->setDescription('Checks for imminent Events with missing stream links and notifies the partners by email.')
            ->setHelp('Checks for imminent Events with missing stream links and notifies the partners by email.');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if( $singleEvents = $this->singleEventRepository->getUnnotifiedImminentEventsWithoutStreamLink()) {
            $updatedEvents = 0;
            foreach ( $singleEvents as $singleEvent) {
                // notify
                try {
                    $notificationCounter = $singleEvent->getProperty(self::NOTIFIED_STREAMLINK) === '1' ? '2' : '1';
                    if( $this->notificationService->sendCourseLinkNotification($singleEvent, $notificationCounter)) {
                        $singleEvent->setProperty( self::NOTIFIED_STREAMLINK, 'text', $notificationCounter);
                        $singleEvent->save();
                        $updatedEvents++;
                    }
                } catch (Exception $e) {
                    Simple::log('missinglinknotifications', sprintf('Exception: %s', $e->getMessage()));
                }
            }
            Simple::log('missinglinknotifications',  sprintf("Missing Link Events: %d of which were notified in this run: %d", count($singleEvents), $updatedEvents));
        }
        return 0;
    }

}
