<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Assets\GalleryUpdateDto;
use App\Dto\VPP\Assets\GalleryInputDto;
use App\Entity\PartnerProfile;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\Fieldcollection\Data\ImageDescriptionBlock;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


class AssetsVppGalleryUpdateDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param GalleryUpdateDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : PartnerProfile
    {
        $this->validator->validate($object);

        /** @var PartnerProfile $partner */
        $partner = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        $this->partnerProfileService->checkIfChangesAreAllowed($partner);


        if(count($object->gallery) == 0) {
            $partner->setStudioImage(null);
            $partner->setStudioImageTitle(null, 'de');
            $partner->setStudioImageTitle(null, 'en');
        }

        $collection = new Fieldcollection();
        foreach ($object->gallery as $index => $galleryElement) {

            if ($index === 0) {
                if (isset($galleryElement->assetId)) {
                    $partner->setStudioImage(Image::getById($galleryElement->assetId));
                }

                if (isset($galleryElement->title)) {
                    $partner->setStudioImageTitle($galleryElement->title->de, 'de');
                    $partner->setStudioImageTitle($galleryElement->title->en, 'en');
                }
            }
            else {
                $collection->add($this->getGalleryBlock($galleryElement));
            }
        }

        $partner->setGallery($collection);
        $partner->save();

        return $partner;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof PartnerProfile) {
            return false;
        }

        return $to === PartnerProfile::class && (
                ($data instanceof GalleryUpdateDto) ||
                ($context['input']['class'] ?? null) === GalleryUpdateDto::class
            );
    }

    private function getGalleryBlock(GalleryInputDto $galleryElement): ImageDescriptionBlock {
        $block = new ImageDescriptionBlock();

        if (isset($galleryElement->assetId)) {
            $block->setImage(Image::getById($galleryElement->assetId));
        }

        if (isset($galleryElement->title)) {
            $block->setTitle($galleryElement->title->de, 'de');
            $block->setTitle($galleryElement->title->en, 'en');
        }

        return $block;
    }
}
