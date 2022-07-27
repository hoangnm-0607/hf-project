<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\PartnerCategory;
use LimitIterator;

class PartnerCategoryCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use ListingCollectionTrait;

    private int $itemsPerPage;

    public function __construct(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $condition = [];

        list ($list, $firstResult) = $this->getListingAndFirstResult(\Pimcore\Model\DataObject\PartnerCategory::class, $context, $condition);

        // If the api is aclled with page parameter, limit the output accordingly. Otherwise, dump the complete dataset.
        if(isset($context['filters']['page'])) {
            $iterator = new LimitIterator($list, $firstResult, $this->itemsPerPage);
        } else {
            $iterator = $list;
        }
        foreach ($iterator as $entry) {
            yield $entry;
        }
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === PartnerCategory::class;
    }

}
