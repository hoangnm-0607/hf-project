<?php

declare(strict_types=1);

namespace App\DataProvider\Company;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DataProvider\ListingCollectionTrait;
use Pimcore\Model\DataObject\CompanyFileCategory;

class CompanyFileCategoryListCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use ListingCollectionTrait;

    private int $itemsPerPage;

    public function __construct(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {

        $listing = new CompanyFileCategory\Listing;

        if(isset($context['filters']['page'])) {
            $this->setPagination($listing, $this->itemsPerPage, intval($context['filters']['page']));
        }

        return $listing;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return \App\Entity\CompanyFileCategory::class === $resourceClass && 'get-company-file-categories' === $operationName;
    }
}
