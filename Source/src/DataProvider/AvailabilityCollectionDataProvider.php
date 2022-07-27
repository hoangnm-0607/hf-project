<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Availability;
use LimitIterator;
use Pimcore\Model\DataObject\SingleEvent;

class AvailabilityCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use ListingCollectionTrait;

    private int $itemsPerPage;

    public function __construct(int $itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        list ($list, $firstResult) = $this->getListingAndFirstResult(SingleEvent::class, $context, []);

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
        return $resourceClass === Availability::class;
    }

}
