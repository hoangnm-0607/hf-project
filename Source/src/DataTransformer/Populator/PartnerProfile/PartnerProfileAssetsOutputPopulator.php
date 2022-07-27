<?php


namespace App\DataTransformer\Populator\PartnerProfile;


use App\DataProvider\Helper\AssetHelper;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use App\Service\I18NService;
use Pimcore\Model\DataObject\Fieldcollection\Data\ImageDescriptionBlock;

class PartnerProfileAssetsOutputPopulator
{
    private AssetHelper $assetHelper;
    private I18NService $i18NService;

    public function __construct(AssetHelper $assetHelper, I18NService $i18NService)
    {
        $this->assetHelper = $assetHelper;
        $this->i18NService = $i18NService;
    }

    public function populate(PartnerProfile $source, $target): PartnerProfileDto
    {
        $language = $this->i18NService->getLanguageFromRequest();

        $target->logo = $source->getLogo() ? $this->assetHelper->getAssetUrl($source->getLogo()) : null;
        $this->setStudioImage($target, $source, $language);
        $this->setStudioGallery($target, $source, $language);
        $this->setStudioVideo($target, $source, $language);

        return $target;
    }


    private function setStudioImage(PartnerProfileDto $target, PartnerProfile $source, string $language)
    {
        $studioImage = $source->getStudioImage();
        $studioImageTitle = $source->getStudioImageTitle($language);

        if(null !== $studioImage) {
            $target->studioImage = [
                'url' => $this->assetHelper->getAssetUrl($studioImage),
                'title' => $studioImageTitle
            ];
        }
    }

    private function setStudioGallery(PartnerProfileDto $target, PartnerProfile $source, string $language)
    {
        $galleryItems = [];
        if ($studioGallery = $source->getGallery()) {
            /** @var ImageDescriptionBlock $galleryItem */
            foreach ($studioGallery as $galleryItem) {
                $item = [
                    'url' => $this->assetHelper->getAssetUrl($galleryItem->getImage()),
                    'title' => $galleryItem->getTitle($language),
                ];
                $galleryItems[] = $item;
            }
        }
        $target->pictures = $galleryItems;
    }

    private function setStudioVideo(PartnerProfileDto $target, PartnerProfile $source, string $language)
    {


        if($video = $source->getStudioVideo()) {
            $videoType = $video->getType();

            $url = null;
            if ($videoType === 'youtube') {
                $url = 'https://www.youtube.com/watch?v=' . $video->getData();
            }
            elseif($videoType === 'vimeo') {
                $url = 'https://player.vimeo.com/video/' . $video->getData();
            }
            elseif ($videoType === 'asset') {
                $url = $this->assetHelper->getAssetUrl($video->getData());
            }

            $target->video = [
                'url' => $url,
                'title' => $source->getStudioVideoTitle($language),
                'type' => $videoType
            ];

        }
    }
}
