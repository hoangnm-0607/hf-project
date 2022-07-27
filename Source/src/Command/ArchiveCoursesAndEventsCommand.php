<?php


namespace App\Command;


use App\Repository\SingleEventRepository;
use App\Service\ArchiveService;
use Exception;
use Pimcore\Console\AbstractCommand;
use Pimcore\Log\Simple;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArchiveCoursesAndEventsCommand extends AbstractCommand
{
    private SingleEventRepository $singleEventRepository;
    private ArchiveService $archiveService;

    public function __construct(SingleEventRepository $singleEventRepository, ArchiveService $archiveService, string $name = null)
    {
        $this->singleEventRepository = $singleEventRepository;
        $this->archiveService = $archiveService;

        parent::__construct($name);
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:archive')
            ->setDescription('Archives Courses and SingleEvents objects')
            ->setHelp('24 hours after an event has started, the event objects will be moved to an archive folder.');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if( $list = $this->singleEventRepository->getExpiredSingleEvents()) {
            foreach ($list as $item) {
                try {
                    $this->archiveService->archiveCourseOrEvent($item);
                } catch (Exception $e) {
                    Simple::log('archiveException', sprintf('Exception: %s', $e->getMessage()));
                }
            }
        }

        return 0;
    }

}
