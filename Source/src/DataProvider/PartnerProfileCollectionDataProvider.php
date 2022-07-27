<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataCollection\DatedPartnerListing;
use App\Entity\PartnerProfile;

class PartnerProfileCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use ListingCollectionTrait;

    private int $itemsPerPage;

    public function __construct(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return PartnerProfile::class === $resourceClass && $operationName == 'get';
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $listing = new DatedPartnerListing();

        if (isset($context['filters']['lastUpdateTimestamp']) && $timestamp = $context['filters']['lastUpdateTimestamp']) {
            $listing->setCondition('o_modificationDate > (?)', $timestamp);
        }

        $listing->setUnpublished(true)->setOrderKey('o_modificationDate')->setOrder('DESC');
        if(isset($context['filters']['page'])) {
            $this->setPagination($listing, $this->itemsPerPage, $context['filters']['page']);
        }

        return $listing;
    }

}
