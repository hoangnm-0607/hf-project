<?php


namespace App\DataTransformer\Populator\Courses;


use App\DataProvider\Helper\AssetHelper;
use App\Dto\CoursesDto;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Service\DataObjectService;
use App\Service\I18NService;
use App\Service\TranslatorService;

final class CoursesOutputPopulator implements CoursesOutputPopulatorInterface
{
    private I18NService $i18NService;
    private AssetHelper $assetHelper;
    private DataObjectService $dataObjectService;
    private TranslatorService $translator;

    public function __construct(AssetHelper $assetHelper, DataObjectService $dataObjectService, I18NService $i18NService, TranslatorService $translator)
    {
        $this->assetHelper       = $assetHelper;
        $this->dataObjectService = $dataObjectService;
        $this->i18NService       = $i18NService;
        $this->translator = $translator;
    }

    public function populate(Courses $source, CoursesDto $target, $userId): CoursesDto
    {
        /** @var PartnerProfile $partner */
        if($partner = $this->dataObjectService->getRecursiveParent($source, PartnerProfile::class)) {

            $language = $this->i18NService->getLanguageFromRequest();

            $target->courseId  = $source->getCourseID() ?? '';

            $target->courseName = $source->getCourseName($language) ?? '';
            $target->courseType = $source->getCourseType() ?? '';
            $target->shortDescription = $source->getDescription($language) ?? '';
            $target->courseImage = $this->assetHelper->getAssetUrl($source->getCourseImage());
            $target->level = implode(',', $this->translator->getTranslatedValues($source->getLevel(), $language));
            $target->neededAccessoires = $source->getNeededAccessoires($language);
            $target->courseDuration = $source->getDuration();
            $target->exclusiveCourse = $source->getExclusiveCourse() ?? false;
            $target->courseInstructor = $source->getCourseInstructor();

            $target->packageId = ($source->getCourseType() === 'OnlineCourse' && $source->getExclusiveCourse()) ? 30 : 31;

            $target->packageId = ($source->getCourseType() === 'OnlineCourse' && $source->getExclusiveCourse()) ? 30 : 31;

            $target->packageId = ($source->getCourseType() === 'OnlineCourse' && $source->getExclusiveCourse()) ? 30 : 31;

            $target->published = $source->isPublished();

            $target->partnerId = $partner->getCASPublicID() ?? '';
            $target->partnerName = $partner->getName() ?? '';
            $target->partnerDescription = $partner->getShortDescription($language) ?? '';

        }

        return $target;
    }
}
