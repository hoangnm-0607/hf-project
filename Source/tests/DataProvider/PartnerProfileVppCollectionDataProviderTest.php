<?php

namespace Tests\DataProvider;

use App\DataProvider\PartnerProfileVppCollectionDataProvider;
use App\Entity\PartnerProfile;
use App\Service\InMemoryUserReaderService;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\PartnerProfile\Listing;

class PartnerProfileVppCollectionDataProviderTest extends TestCase
{
    private InMemoryUserReaderService $inMemoryUserReaderService;
    private PartnerProfileVppCollectionDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->inMemoryUserReaderService = $this->createMock(InMemoryUserReaderService::class);
        $this->dataProvider = new PartnerProfileVppCollectionDataProvider($this->inMemoryUserReaderService);
    }

    public function testSupports()
    {
        $isSupports = $this->dataProvider->supports(PartnerProfile::class, 'get_names');
        self::assertTrue($isSupports);
    }

    public function testGetCollection()
    {
        $this->inMemoryUserReaderService->method('getUserIdentifier')->willReturn([
            'status' => [
                'publicId' => '42'
            ]
        ]);

        $result = $this->dataProvider->getCollection(PartnerProfile::class);
        self::assertEquals(Listing::class, get_class((object) $result));
    }
}
