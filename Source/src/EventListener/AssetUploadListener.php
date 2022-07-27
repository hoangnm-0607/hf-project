<?php


namespace App\EventListener;


use App\Entity\PartnerProfile;
use App\Entity\EndUser;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Event\Model\Asset\ResolveUploadTargetEvent;
use Pimcore\Model\DataObject;

class AssetUploadListener
{
    private PartnerProfileService $partnerProfileService;

    public function __construct(PartnerProfileService $partnerProfileService)
    {
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @throws Exception
     */
    public function resolveUpload(ResolveUploadTargetEvent $event): void
    {
        if (($context = $event->getContext())
            && ($objectId = $context['objectId'])) {
            $dataObject = DataObject::getById($objectId);
            if ($dataObject->getClassName() == 'EndUser') {
                $endUser = EndUser::getById($objectId);
                $event->setParentId($endUser->getAssetFolder()->getId());
            }
            elseif ($dataObject->getClassName() == 'PartnerProfile') {
                $partnerProfile = PartnerProfile::getById($objectId);
                $this->partnerProfileService->checkForCasPublicIdAndCreateAndSetAssetFolders($partnerProfile, $event);
            }

        }
    }

}
