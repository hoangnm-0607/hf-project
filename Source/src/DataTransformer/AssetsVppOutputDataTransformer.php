<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\VPP\Assets\AssetsVppOutputDto;
use App\Entity\PartnerProfile;
use Exception;

class AssetsVppOutputDataTransformer implements DataTransformerInterface
{

    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    /**
     * @param PartnerProfile $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : AssetsVppOutputDto
    {
        $target = new AssetsVppOutputDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return AssetsVppOutputDto::class === $to && $data instanceof PartnerProfile;
    }
}
