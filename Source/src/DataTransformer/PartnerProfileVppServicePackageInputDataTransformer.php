<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Partners\ServicePackageDto;
use App\Entity\PartnerProfile;
use App\Entity\ServicePackage;
use App\Service\PartnerProfileService;
use Exception;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PartnerProfileVppServicePackageInputDataTransformer implements DataTransformerInterface
{
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param ServicePackageDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : PartnerProfile
    {
        $this->validator->validate($object);

        /** @var PartnerProfile $partner */
        $partner = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        $this->partnerProfileService->checkIfChangesAreAllowed($partner);

        $servicePackages = $partner->getServicePackages() ?? [];
        $servicePackages = array_values(array_filter($servicePackages, function ($servicePackage) use($object) {
            if ($servicePackage->getName() != $object->servicePackageName) {
                return true;
            }
            return false;
        }));
        $servicePackages[] = ServicePackage::getByName($object->servicePackageName, 1);
        $partner->setServicePackages($servicePackages);

        $partner->save();

        return $partner;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof PartnerProfile) {
            return false;
        }

        return $to === PartnerProfile::class && (
                ($data instanceof ServicePackageDto) ||
                ($context['input']['class'] ?? null) === ServicePackageDto::class
            ) && $context['item_operation_name'] === 'put_service_package';
    }
}
