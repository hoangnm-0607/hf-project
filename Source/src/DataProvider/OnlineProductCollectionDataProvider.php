<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\OnlineProduct;
use App\Security\Validator\InMemoryUserValidator;
use Exception;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\OnlineProduct as DataObjectOnlineProduct;
use Symfony\Component\HttpFoundation\RequestStack;

class OnlineProductCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private RequestStack $requestStack;
    private InMemoryUserValidator $inMemoryUserValidator;

    public function __construct(RequestStack $requestStack, InMemoryUserValidator $inMemoryUserValidator)
    {
        $this->requestStack = $requestStack;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
    }

    /**
     * @throws Exception
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $casUserId = $this->requestStack->getCurrentRequest()->attributes->get('casUserId');
        $this->inMemoryUserValidator->validateTokenAndApiUserId($casUserId);

        $listing = new DataObjectOnlineProduct\Listing;
        $listing->setOrderKey(sprintf('o_%s', AbstractObject::OBJECT_CHILDREN_SORT_BY_INDEX));

        return $listing;
    }


    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === OnlineProduct::class && $operationName == 'get_products';
    }
}
