<?php

namespace App\EventListener;

use App\Service\FolderService;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Company;

class CompanyCreateListener
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
        if (($company = $event->getObject()) && $company instanceof Company) {
            $endUsersFolder = $this->folderService->getOrCreateEndUsersFolder();
            $companyEndUsersFolder = $this->folderService->createDataObjectFolder($endUsersFolder, $company->getKey());
            $company->setEndUserFolder($companyEndUsersFolder);
            $company->save();

            $this->folderService->getOrCreateCustomFieldsSubFolderForCompany($company);

            $this->folderService->createDocumentFolderForCompany($company);
        }
    }

}
