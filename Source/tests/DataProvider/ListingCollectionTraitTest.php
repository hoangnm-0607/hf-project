<?php

namespace Tests\DataProvider;

use App\DataProvider\ListingCollectionTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Booking;

class ListingCollectionTraitTest extends TestCase
{

    protected ListingCollectionTrait|MockObject $listingCollectionMock;

    protected function setUp(): void
    {
        $this->listingCollectionMock = $this->getMockForTrait('\App\DataProvider\ListingCollectionTrait');
        $this->listingCollectionMock->setItemsPerPage(10);
    }

    public function testGetListingAndFirstResult()
    {
        $context['filters']['page'] = 1;

        $condition = [
            'o_modificationDate > (?)',
            123456123
        ];
        $result = $this->listingCollectionMock->getListingAndFirstResult(Booking::class, $context, $condition);

        self::assertIsArray($result);

    }
}
