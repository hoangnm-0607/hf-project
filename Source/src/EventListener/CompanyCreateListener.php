<?php

namespace App\EventListener;

use App\Service\CAS\CasCommunicationService;
use App\Service\FolderService;
use App\Traits\RequestStackTrait;
use App\Traits\TranslatorTrait;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\Element\ValidationException;

class CompanyCreateListener
{
    use RequestStackTrait;
    use TranslatorTrait;

    private FolderService $folderService;
    private CasCommunicationService $casCommunicationService;

    public function __construct(FolderService $folderService, CasCommunicationService $casCommunicationService)
    {
        $this->folderService = $folderService;
        $this->casCommunicationService = $casCommunicationService;
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

        }
    }

    public function createCasCompany(DataObjectEvent $event): void
    {
        $company = $event->getObject();

        if (
            $company instanceof Company
            && null === $company->getCasCompanyId()
            && false === $this->isCallFromAutoSave($event)
            && $company->isPublished()
            && 'publish' === $this->requestStack->getCurrentRequest()?->get('task')
        ) {
            $casData = $this->casCommunicationService->createCasDataForNewCompany($company);

            if (\is_array($casData) && isset($casData['companyId'])) {
                $company->setCasCompanyId($casData['companyId']);
                $company->save();
            } else {
                $company->setPublished(false);
                $company->save();

                throw new ValidationException($this->translator->trans('admin.object.company.message.cantCasCreate', [], 'admin'));
            }
        }
    }

    private function isCallFromAutoSave(DataObjectEvent $event): bool
    {
        $arguments = $event->getArguments();
        if (\is_array($arguments) && isset($arguments['isAutoSave'])) {
            return true;
        }

        return false;
    }
}
