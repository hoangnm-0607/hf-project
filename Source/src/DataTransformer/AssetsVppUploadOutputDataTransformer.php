<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\VPP\Assets\AssetDto;
use App\Entity\LastUsedAssertIdInterface;

class AssetsVppUploadOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param LastUsedAssertIdInterface $object
     */
    public function transform($object, string $to, array $context = []) : AssetDto
    {
        $target = new AssetDto();

        $target->assetId = $object->getLastAssetId();

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return AssetDto::class === $to && $data instanceof LastUsedAssertIdInterface;
    }
}
