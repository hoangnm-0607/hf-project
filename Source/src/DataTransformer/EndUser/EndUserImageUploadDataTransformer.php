<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\VPP\Assets\AssetUploadDto;
use App\Entity\EndUser;
use App\Helper\ConstHelper;
use App\Service\FolderService;
use App\Traits\ValidatorTrait;
use Pimcore\Model\Asset\Image;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EndUserImageUploadDataTransformer implements DataTransformerInterface
{
    use ValidatorTrait;

    private FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @param AssetUploadDto $object
     */
    public function transform($object, string $to, array $context = []) : EndUser
    {
        $this->validator->validate($object);

        /** @var EndUser $endUser */
        $endUser = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $folder = $this->folderService->getOrCreateAssetFolderForEndUser($endUser);

        $asset = new Image();
        $asset->setParent($folder);
        $asset->setFilename($object->filename);

        $asset->setProperty('originalFilename', 'image',$object->originalFilename ?? $object->filename);

        $asset->save(["versionNote" => "API upload"]);

        $endUser->setLastAssetId($asset->getId());

        return $endUser;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return
            $to === EndUser::class
            && (($data instanceof AssetUploadDto) || ($context['input']['class'] ?? null) === AssetUploadDto::class)
            && isset($context['item_operation_name'])
            && 'put_image'.ConstHelper::AS_ADMIN === $context['item_operation_name']
        ;
    }
}
