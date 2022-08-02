<?php

namespace App\EventListener;

use App\Service\CAS\CasCommunicationService;
use App\Service\FolderService;
use App\Traits\RequestStackTrait;
use App\Traits\TranslatorTrait;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\EndUser;
use Pimcore\Model\Element\ValidationException;

class EndUserCreateListener
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
    public function createAssetFolders(DataObjectEvent $event): void
    {
        if (($endUser = $event->getObject()) && $endUser instanceof EndUser) {
            $endUsersFolder = $this->folderService->getOrCreateAssetFolderForEndUser($endUser);
            $endUser->setAssetFolder($endUsersFolder);
            $endUser->save();
        }
    }

    public function createCasEndUser(DataObjectEvent $event): void
    {
        $endUser = $event->getObject();
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (
            $endUser instanceof EndUser
            && null === $endUser->getCasUserId()
            && false === $this->isCallFromAutoSave($event)
            && $endUser->isPublished()
            && (
                'publish' === $currentRequest?->get('task')
                || 'api_end_users_create.as_manager_collection' === $currentRequest?->get('_route')
            )
        ) {
            $casData = $this->casCommunicationService->createCasDataForNewEndUser($endUser);

            if (\is_array($casData) && isset($casData['customerId'])) {
                $endUser
                    ->setCasUserId($casData['customerId'])
                    ->setActivationKey($casData['activationKey'])
                    ->setHashedUserId($casData['publicId'])
                ;
                $endUser->save();
            } else {
                $endUser->setPublished(false);
                $endUser->save();

                throw new ValidationException($this->translator->trans('admin.object.enduser.message.cantCasCreate', [], 'admin'));
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
