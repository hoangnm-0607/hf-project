<?php


namespace App\Service;


use App\Entity\ServicePackage;
use App\Exception\CourseManagerInactiveException;
use App\Exception\TerminationReachedException;
use Carbon\Carbon;
use Exception;
use Pimcore\Event\Model\Asset\ResolveUploadTargetEvent;
use Pimcore\Model\DataObject\Folder as ObjectFolder;
use Pimcore\Model\DataObject\PartnerProfile;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PartnerProfileService
{

    private FolderService $folderService;
    private TranslatorInterface $translator;

    public function __construct(FolderService $folderService, TranslatorInterface $translator)
    {
        $this->folderService = $folderService;
        $this->translator = $translator;
    }

    /**
     * @throws Exception
     */
    public function createPartnerProfileAndFolders(string $partnerName): PartnerProfile
    {
        $rootFolder    = ObjectFolder::getByPath('/' . FolderService::PARTNERFOLDER);

        if ( !$profile = PartnerProfile::getByPath($rootFolder . '/' . $partnerName)) {
            $profile = new PartnerProfile();
            $profile->setKey($partnerName);
            $profile->setParent($rootFolder);
            $profile->save();

            $this->folderService->createDataObjectFolder($profile, FolderService::COURSESFOLDER);
            $this->folderService->createDataObjectFolder($profile, FolderService::ARCHIVEFOLDER);
        }
        return $profile;
    }


    /**
     * @throws Exception
     */
    public function checkForCasPublicIdAndCreateAndSetAssetFolders(PartnerProfile $partnerProfile, ResolveUploadTargetEvent $event): void {
        if ($partnerProfile->getCASPublicID()) {
            $assetFolder = $partnerProfile->getAssetFolder();
            if ($assetFolder == null){
                $assetFolder = $this->folderService->getOrCreateAssetFolderForPartnerProfile($partnerProfile);

                $partnerProfile->setAssetFolder($assetFolder);
                $partnerProfile->save();
            }

            if ($event->getContext()['fieldname'] == 'Logo') {
                $assetFolder = $this->folderService->getOrCreateLogoAssetFolderForPartnerProfile($partnerProfile);
            }
            elseif ($event->getContext()['fieldname'] == 'StudioImage' ||
                ($event->getContext()['fieldname'] == 'Image' && $event->getContext()['containerName'] == 'Gallery')) {
                $assetFolder = $this->folderService->getOrCreateGalleryAssetFolderForPartnerProfile($partnerProfile);
            }
            $event->setParentId($assetFolder->getId());
        } else {
            throw new AccessDeniedHttpException(
                $this->translator->trans('admin.course.upload.message.missingCasPublicId', [], 'admin')
            );
        }
    }

    public function getProfileCompletionPercentage(PartnerProfile $partnerProfile): int {
        $percentage = 0;

        if ($partnerProfile->getName() && $partnerProfile->getStreet() && $partnerProfile->getNumber()
            && $partnerProfile->getZip() && $partnerProfile->getCity() && $partnerProfile->getCountry()
            && $partnerProfile->getTelephone() && $partnerProfile->getEmail() && $partnerProfile->getWebsite()) {
            $percentage += 20;
        }
        if ($partnerProfile->getCheckInApp() || $partnerProfile->getCheckInCard()) {
            $percentage += 10;
        }
        if ($partnerProfile->getPartnerCategoryPrimary()) {
            $percentage += 10;
        }
        if ($partnerProfile->getShortDescription('de') && $partnerProfile->getShortDescription('en')) {
            $percentage += 10;
        }
        if (!$partnerProfile->getOpeningTimes()->isEmpty()) {
            $percentage += 10;
        }
        if ($partnerProfile->getFitnessServicesInclusive() || $partnerProfile->getWellnessServicesInclusive() || $partnerProfile->getServicesInclusive()
        || $partnerProfile->getFitnessServicesSurcharge() || $partnerProfile->getWellnessServicesSurcharge() || $partnerProfile->getServicesSurcharge()) {
            $percentage += 10;
        }
        if ($partnerProfile->getLogo()) {
            $percentage += 10;
        }
        if ($partnerProfile->getStudioImage()) {
            $percentage += 10;
        }
        if ($partnerProfile->getGallery()) {
            $percentage += 5;
        }
        if ($partnerProfile->getStudioVideo()) {
            $percentage += 5;
        }

        return $percentage;
    }

    /**
     * @throws TerminationReachedException
     */
    public function checkIfChangesAreAllowed(PartnerProfile $partnerProfile) {
        if (($terminationDate = $partnerProfile->getTerminationDate()) && $terminationDate->lt(Carbon::today())) {
            throw new TerminationReachedException("The termination date is reached for this partner. Updates are no longer allowed");
        }
    }

    /**
     * @throws CourseManagerInactiveException
     */
    public function checkIfCourseManagerIsActive(PartnerProfile $partnerProfile) {
        $servicePackages = $partnerProfile->getServicePackages() ?? [];
        foreach ($servicePackages as $servicePackage) {
            if ($servicePackage->getName() == ServicePackage::ONLINE_COURSE_SERVICE_PACKAGE) {
                return true;
            }
        }

        throw new CourseManagerInactiveException("Course Manager is not active for this partner");
    }


}
