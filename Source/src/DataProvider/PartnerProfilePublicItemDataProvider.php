<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Repository\PartnerProfileRepository;
use App\Entity\PartnerProfile;

class PartnerProfilePublicItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private PartnerProfileRepository $profileRepository;

    public function __construct(PartnerProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }


    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?PartnerProfile
    {

        $partnerProfile = $this->profileRepository->getOnePartnerProfileByCasPublicId($id);

        return $partnerProfile instanceof PartnerProfile ? $partnerProfile : null;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === PartnerProfile::class && $operationName == 'get_details';
    }
}
