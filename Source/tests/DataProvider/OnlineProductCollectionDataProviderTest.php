<?php

namespace Tests\DataProvider;

use App\DataProvider\OnlineProductCollectionDataProvider;
use App\Entity\OnlineProduct;
use App\Security\Validator\InMemoryUserValidator;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\OnlineProduct\Listing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class OnlineProductCollectionDataProviderTest extends TestCase
{
    private RequestStack $requestStack;
    private InMemoryUserValidator $inMemoryUserValidator;
    private OnlineProductCollectionDataProvider $onlineProductCollectionDataProvider;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->inMemoryUserValidator = $this->createMock(InMemoryUserValidator::class);
        $this->onlineProductCollectionDataProvider = new OnlineProductCollectionDataProvider(
            $this->requestStack,
            $this->inMemoryUserValidator
        );
    }

    public function testSupports()
    {
        $isSupports = $this->onlineProductCollectionDataProvider->supports(OnlineProduct::class, 'get_products');
        self::assertTrue($isSupports);
    }

    public function testGetCollection()
    {
        $casUserId = '42';
        $this->requestStack->method('getCurrentRequest')->willReturn(new Request(
            attributes: [
                'casUserId' => $casUserId,
            ]
        ));
        $this->inMemoryUserValidator->method('validateTokenAndApiUserId')->willReturn(null);

        $result = $this->onlineProductCollectionDataProvider->getCollection(OnlineProduct::class, null, []);

        self::assertEquals(Listing::class, get_class((object) $result));
    }
}
