<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\VPP\Partners\PartnerNameDto;
use Pimcore\Model\DataObject\PartnerProfile;

class PartnerProfileNamesVppOutputDataTransformer implements DataTransformerInterface
{

    /**
     * @param PartnerProfile $object
     */
    public function transform($object, string $to, array $context = []): PartnerNameDto
    {
        $target = new PartnerNameDto();

        $target->publicId = $object->getCASPublicID();
        $target->studioName = $object->getKey();

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to == PartnerNameDto::class && $data instanceof PartnerProfile;
    }

}
