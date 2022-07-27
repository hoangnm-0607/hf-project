<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Assets\AssetDto;
use App\Entity\PartnerProfile;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\Asset\Image;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AssetsVppLogoUpdateInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param AssetDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : PartnerProfile
    {
        $this->validator->validate($object);

        /** @var PartnerProfile $partner */
        $partner = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        $this->partnerProfileService->checkIfChangesAreAllowed($partner);

        $asset = Image::getById($object->assetId);
        $partner->setLogo($asset);

        $partner->save();

        return $partner;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof PartnerProfile) {
            return false;
        }

        return $to === PartnerProfile::class && (
                ($data instanceof AssetDto) ||
                ($context['input']['class'] ?? null) === AssetDto::class
            ) && $context['item_operation_name'] === 'patch_asset_logo';
    }
}
