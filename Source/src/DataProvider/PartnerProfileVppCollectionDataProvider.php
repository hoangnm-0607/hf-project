<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\PartnerProfile;
use App\Exception\PartnerRoleStatusNotFoundException;
use App\Exception\TokenIdMismatchException;
use App\Service\InMemoryUserReaderService;
use Pimcore\Model\DataObject\PartnerProfile as DataObjectPartnerProfile;

class PartnerProfileVppCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private InMemoryUserReaderService $inMemoryUserReaderService;

    public function __construct(InMemoryUserReaderService $inMemoryUserReaderService)
    {
        $this->inMemoryUserReaderService = $inMemoryUserReaderService;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return PartnerProfile::class === $resourceClass && $operationName == 'get_names';
    }

    /**
     * @throws TokenIdMismatchException
     * @throws PartnerRoleStatusNotFoundException
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $partnerRoleStatus = $this->inMemoryUserReaderService->getUserIdentifier();
        if ($partnerRoleStatus == null) {
            throw new PartnerRoleStatusNotFoundException();
        }

        $publicIds = array_map(function ($row){
            return $row['publicId'];
            },
            $partnerRoleStatus
        );

        $listing = new DataObjectPartnerProfile\Listing;
        $listing->setCondition('CASPublicID in (?)', [$publicIds]);
        return $listing;
    }

}
