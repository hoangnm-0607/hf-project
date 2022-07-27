<?php


namespace App\DataTransformer\Populator\CourseCategory;


use App\DataProvider\Helper\AssetHelper;
use App\Dto\CourseCategoryDto;
use App\Service\I18NService;

class CourseCategoryOutputPopulator implements CourseCategoryOutputPopulatorInterface
{

    private I18NService $i18NService;
    private AssetHelper $assetHelper;

    public function __construct(AssetHelper $assetHelper, I18NService $i18NService)
    {
        $this->assetHelper = $assetHelper;
        $this->i18NService = $i18NService;
    }

    public function populate($source, CourseCategoryDto $target): CourseCategoryDto
    {
        $language = $this->i18NService->getLanguageFromRequest();

        $target->id = $source->getId();
        $target->shortDescription = $source->getName($language) ?? '';
        $target->longDescription = $source->getDescription($language);
        $target->iconUrlContour = $this->assetHelper->getAssetUrl($source->getCategoryIconContour());
        $target->iconUrlFilled = $this->assetHelper->getAssetUrl($source->getCategoryIconFilled());
        $target->imageUrl = $this->assetHelper->getAssetUrl($source->getCategoryImage());

        return $target;
    }
}
