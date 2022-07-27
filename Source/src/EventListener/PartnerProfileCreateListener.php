<?php

namespace App\EventListener;

use App\Entity\PartnerProfile;
use App\Service\FolderService;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;

class PartnerProfileCreateListener
{
    private FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @throws Exception
     */
    public function createObjectFolders(DataObjectEvent $event): void
    {
        if (($partnerProfile = $event->getObject()) && $partnerProfile instanceof PartnerProfile) {
            // create partnerfolder (courses, archive)
            $this->folderService->createDataObjectFolder($partnerProfile, FolderService::COURSESFOLDER);
            $this->folderService->createDataObjectFolder($partnerProfile, FolderService::ARCHIVEFOLDER);
        }
    }

}
