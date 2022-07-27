<?php

namespace App\EventListener;

use App\Service\FolderService;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\EndUser;

class EndUserCreateListener
{
    private FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @throws Exception
     */
    public function createAssetFolders(DataObjectEvent $event): void
    {
        if (($endUser = $event->getObject()) && $endUser instanceof EndUser) {
            $endUsersFolder = $this->folderService->getOrCreateAssetFolderForEndUser($endUser);
            $endUser->setAssetFolder($endUsersFolder);
            $endUser->save();
        }
    }

}
