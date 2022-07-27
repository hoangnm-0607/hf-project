<?php


namespace App\DataTransformer\Populator\PartnerProfile;


use App\Dto\VPP\Assets\AssetsVppOutputDto;
use App\Dto\VPP\Assets\VideoDto;
use App\Dto\VPP\Assets\VideoSettingsDto;
use App\Dto\VPP\Assets\GalleryOutputDto;
use App\Dto\VPP\Assets\LogoOutputDto;
use App\Dto\VPP\Assets\TitleDto;
use App\Entity\PartnerProfile;
use Pimcore\Model\Asset\Video;
use Pimcore\Model\DataObject\Fieldcollection\Data\ImageDescriptionBlock;

class AssetsVppOutputPopulator implements AssetsVppOutputPopulatorInterface
{

    public function populate(PartnerProfile $source, AssetsVppOutputDto $target): AssetsVppOutputDto
    {
        $this->setLogo($target, $source);
        $this->setStudioGallery($target, $source);
        $this->setStudioVideo($target, $source);

        return $target;
    }


    private function setLogo(AssetsVppOutputDto $target, PartnerProfile $source)
    {
        $studioLogo = $source->getLogo();

        $logo = new LogoOutputDto();
        $logo->assetId = $studioLogo?->getId();
        $logo->uri = $studioLogo?->getFilename();
        $logo->originalFilename = $studioLogo?->getProperty('originalFilename') ?? $logo->uri;

        $target->logo = $logo;
    }


    private function setStudioGallery(AssetsVppOutputDto $target, PartnerProfile $source)
    {
        $studioImage = new GalleryOutputDto();
        $studioImage->assetId = $source->getStudioImage()?->getId();
        $studioImage->uri = $source->getStudioImage()?->getFilename();
        $studioImage->originalFilename = $source->getStudioImage()?->getProperty('originalFilename') ?? $studioImage->uri;

        $titleDto = new TitleDto();
        $titleDto->de = $source->getStudioImageTitle('de');
        $titleDto->en = $source->getStudioImageTitle('en');
        $studioImage->title = $titleDto;
        $galleryItems[] = $studioImage;

        if ($studioGallery = $source->getGallery()) {

            /** @var ImageDescriptionBlock $galleryItem */
            foreach ($studioGallery as $galleryItem) {
                $galleryItemDto = new GalleryOutputDto();
                $galleryItemDto->assetId = $galleryItem->getImage()?->getId();
                $galleryItemDto->uri = $galleryItem->getImage()?->getFilename();
                $galleryItemDto->originalFilename = $galleryItem->getImage()?->getProperty('originalFilename') ?? $galleryItemDto->uri;

                $titleDto = new TitleDto();
                $titleDto->de = $galleryItem->getTitle('de');
                $titleDto->en = $galleryItem->getTitle('en');
                $galleryItemDto->title = $titleDto;

                $galleryItems[] = $galleryItemDto;
            }
        }
        $target->gallery = $galleryItems;
    }


    private function setStudioVideo(AssetsVppOutputDto $target, PartnerProfile $source)
    {
        $videoUri = $videoType = $previewUri = $previewUriAssetId = $assetId = $originalFilename = null;
        if($video = $source->getStudioVideo()) {
            $videoType = $video->getType();
            if ($videoType === 'youtube') {
                $videoUri = $video->getData() ? 'https://www.youtube.com/watch?v=' . $video->getData() : '';
            } elseif ($videoType === 'vimeo') {
                $videoUri = $video->getData() ? 'https://player.vimeo.com/video/' . $video->getData() : '';
            } elseif ($videoType === 'asset') {
                $data = $video->getData();
                if ($data instanceof Video) {
                    $videoUri = $video->getData()?->getFilename();
                    $assetId = $video->getData()?->getId();
                    $originalFilename = $video->getData()?->getProperty('originalFilename') ?? $videoUri;
                }
            }
            if ($posterAsset = $video->getPoster()) {
                $previewUri = $posterAsset->getFilename();
                $previewUriAssetId = $posterAsset->getId();
            }
        }

        $titleDto = new TitleDto();
        $titleDto->de = $source->getStudioVideoTitle('de');
        $titleDto->en = $source->getStudioVideoTitle('en');

        $assetVideoSettingsOutputDto = new VideoSettingsDto();
        $assetVideoSettingsOutputDto->uri = $videoUri;
        $assetVideoSettingsOutputDto->assetId = $assetId;
        $assetVideoSettingsOutputDto->type = $videoType;
        $assetVideoSettingsOutputDto->previewUri = $previewUri;
        $assetVideoSettingsOutputDto->previewAssetId = $previewUriAssetId;
        $assetVideoSettingsOutputDto->originalFilename = $originalFilename;

        $assetVideoOutputDto = new VideoDto();
        $assetVideoOutputDto->title = $titleDto;
        $assetVideoOutputDto->videoSettings = $assetVideoSettingsOutputDto;

        $target->video = $assetVideoOutputDto;
    }
}
