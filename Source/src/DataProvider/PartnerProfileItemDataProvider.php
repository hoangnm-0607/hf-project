<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Exception\MissingPublicIdException;
use App\Exception\TokenIdMismatchException;
use App\Repository\PartnerProfileRepository;
use App\Entity\PartnerProfile;
use App\Security\Validator\InMemoryUserValidator;

class PartnerProfileItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private PartnerProfileRepository $profileRepository;
    private InMemoryUserValidator $inMemoryUserValidator;

    public function __construct(PartnerProfileRepository $profileRepository, InMemoryUserValidator $inMemoryUserValidator)
    {
        $this->profileRepository = $profileRepository;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
    }

    /**
     * @throws TokenIdMismatchException
     * @throws MissingPublicIdException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?PartnerProfile
    {
        $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId($id);

        $partnerProfile = $this->profileRepository->getOnePartnerProfileByCasPublicId($id);

        return $partnerProfile instanceof PartnerProfile ? $partnerProfile : null;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === PartnerProfile::class && in_array($operationName, [
                'get', 'update', 'get_assets', 'delete_asset',
                'put_asset_logo', 'patch_asset_logo',
                'put_asset_studiovideo', 'patch_asset_studiovideo',
                'put_asset_gallery', 'update_gallery', 'get_dashboard_stats',
                'put_service_package'
            ]);
    }
}
