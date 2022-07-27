<?php


namespace App\EventListener;


use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Event\Model\Asset\ResolveUploadTargetEvent;
use Pimcore\Model\DataObject\Course;

class CourseAssetUploadListener
{
    private PartnerProfileService $partnerProfileService;

    public function __construct(PartnerProfileService $partnerProfileService)
    {
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @throws Exception
     */
    public function resolveCourseUpload(ResolveUploadTargetEvent $event): void
    {
        if (($context = $event->getContext())
            && ($objectId = $context['objectId'])
            && ($course = Course::getById($objectId))
            && ($partnerProfile = $course->getPartnerProfile())) {
            $this->partnerProfileService->checkForCasPublicIdAndCreateAndSetAssetFolders($partnerProfile, $event);
        }
    }

}
