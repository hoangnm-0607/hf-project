<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Assets\AssetUploadDto;
use App\Entity\PartnerProfile;
use App\Service\FolderService;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\Asset\Video;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AssetsVppUploadDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private FolderService $folderService;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, FolderService $folderService,
                                PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->folderService = $folderService;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param AssetUploadDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : PartnerProfile
    {
        $this->validator->validate($object);

        /** @var PartnerProfile $partner */
        $partner = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        $this->partnerProfileService->checkIfChangesAreAllowed($partner);

        $asset = null;
        if ($context['item_operation_name'] == 'put_asset_logo') {
            $asset = new Image();
            $asset->setParentId($this->folderService->getOrCreateLogoAssetFolderForPartnerProfile($partner)->getId());
        }
        elseif ($context['item_operation_name'] == 'put_asset_studiovideo') {
            $asset = new Video();
            $asset->setParentId($this->folderService->getOrCreateVideoAssetFolderForPartnerProfile($partner)->getId());
        }
        elseif ($context['item_operation_name'] == 'put_asset_gallery') {
            $asset = new Image();
            $asset->setParentId($this->folderService->getOrCreateGalleryAssetFolderForPartnerProfile($partner)->getId());
        }
        $asset->setFilename($object->filename);

        $asset->setProperty('originalFilename', 'text',$object->originalFilename ?? $object->filename);

        $asset->save(["versionNote" => "API upload"]);

        $partner->setLastAssetId($asset->getId());

        return $partner;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof PartnerProfile) {
            return false;
        }

        return $to === PartnerProfile::class && (
                ($data instanceof AssetUploadDto) ||
                ($context['input']['class'] ?? null) === AssetUploadDto::class
            ) && in_array($context['item_operation_name'], ['put_asset_logo', 'put_asset_studiovideo', 'put_asset_gallery']);
    }
}
