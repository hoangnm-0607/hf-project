<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Assets\VideoDto;
use App\Dto\VPP\Assets\VideoSettingsDto;
use App\Entity\PartnerProfile;
use App\Exception\InvalidUrlException;
use App\Exception\UnsupportedVideoTypeException;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\Data\Video;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


class AssetsVppStudioVideoUpdateDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param VideoDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : PartnerProfile
    {
        $this->validator->validate($object);

        /** @var PartnerProfile $partner */
        $partner = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        $this->partnerProfileService->checkIfChangesAreAllowed($partner);

        if (isset($object->videoSettings)) {
            $this->setVideoSettings($object->videoSettings, $partner);
        }

        if (isset($object->title)) {
            $partner->setStudioVideoTitle($object->title->de, 'de');
            $partner->setStudioVideoTitle($object->title->en, 'en');
        }

        $partner->save();

        return $partner;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof PartnerProfile) {
            return false;
        }

        return $to === PartnerProfile::class && (
                ($data instanceof VideoDto) ||
                ($context['input']['class'] ?? null) === VideoDto::class
            ) && $context['item_operation_name'] === 'patch_asset_studiovideo';
    }

    /**
     * @throws UnsupportedVideoTypeException
     * @throws InvalidUrlException
     */
    private function setVideoSettings(VideoSettingsDto $videoSettings, PartnerProfile $partner) {
        $video = $partner->getStudioVideo();
        if (null === $video) {
            $video = new Video();
            $partner->setStudioVideo($video);
        }

        $video->setType($videoSettings->type);

        if (isset($videoSettings->assetId)) {
            $videoAsset = \Pimcore\Model\Asset\Video::getById($videoSettings->assetId);
            $video->setData($videoAsset);
        }
        elseif($video->getType() == 'asset' && !($video->getData() instanceof \Pimcore\Model\Asset\Video)) {
            $video->setData(null);
        }

        if (isset($videoSettings->uri) && $video->getType() != 'asset') {
            $this->setVideoLinks($videoSettings, $video);
        }

        if (isset($videoSettings->previewAssetId) && $videoSettings->type == 'asset') {
            $previewAsset = Image::getById($videoSettings->previewAssetId);
            $video->setPoster($previewAsset);
        }
    }

    /**
     * @throws UnsupportedVideoTypeException
     * @throws InvalidUrlException
     */
    private function setVideoLinks(VideoSettingsDto $videoSettings, Video $video) {
        if ($videoSettings->uri == '') {
            $video->setData('');
        }
        else {
            if ($video->getType() == 'youtube') {
                preg_match('/^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/', $videoSettings->uri, $matches);
                if (isset($matches[7]) && strlen($matches[7]) >= 11) {
                    $video->setData($matches[7]);
                }
                else {
                    throw new InvalidUrlException('Can\'t parse youtube url');
                }
            }
            elseif ($video->getType() == 'vimeo') {
                preg_match('/(?:http:|https:|)\/\/(?:player.|www.)?vimeo\.com\/(?:video\/|embed\/|watch\?\S*v=|v\/)?(\d*)/', $videoSettings->uri, $matches);
                if (isset($matches[1]) && strlen($matches[1]) >= 5) {
                    $video->setData($matches[1]);
                }
                else {
                    throw new InvalidUrlException('Can\'t parse vimeo url');
                }
            }
            else {
                throw new UnsupportedVideoTypeException('Video type ' . $video->getType() . ' is not supported');
            }
        }
    }
}
