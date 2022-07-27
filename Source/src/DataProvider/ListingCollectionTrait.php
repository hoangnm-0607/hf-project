<?php


namespace App\DataProvider;



use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\Listing;

trait ListingCollectionTrait
{

    /**
     * @param string $className
     * @param array  $context
     * @param array  $condition Describes the condition, that's added to the Listing. This is an array, because
     * Pimcore makes use of prepared statements when adding the condition (see
     * https://pimcore.com/docs/pimcore/current/Development_Documentation/Objects/Working_with_PHP_API.html#page_Using-prepared-statement-placeholders-and-variables
     * for more info)
     *
     * @return array
     */
    public function getListingAndFirstResult(string $className, array $context, array $condition = []): array
    {
        $listingClass = $className . '\\Listing';

        /** @var Listing $listing */
        $listing  = new $listingClass();
        $listing->setUnpublished(false)->setOrderKey('o_modificationDate')->setOrder('DESC');

        if ($condition) {
            $listing->setCondition($condition[0], [ $condition[1] ]);
        }

        $firstResult = $this->getFirstResult($context);

        return [$listing, $firstResult];
    }

    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    private function getFirstResult(array $context): int
    {
        $page = $context['filters']['page'] ?? 1;

        return ($page - 1) * $this->itemsPerPage;
    }

    private function setPagination(Listing $listing, int $limit, int $page = 1): void
    {
        $offset = ($page - 1) * $limit;
        $listing->setOffset($offset);
        $listing->setLimit($limit);
    }
}
