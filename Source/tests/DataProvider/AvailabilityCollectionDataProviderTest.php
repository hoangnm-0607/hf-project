<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\AvailabilityCollectionDataProvider;
use App\Entity\Availability;
use PHPUnit\Framework\TestCase;
use stdClass;

class AvailabilityCollectionDataProviderTest extends TestCase
{

    private AvailabilityCollectionDataProvider $availabiltyCollectionDataProvider;

    protected function setUp(): void
    {
        $this->availabiltyCollectionDataProvider = new AvailabilityCollectionDataProvider(20);
    }

    public function testSupportsAvailabilityEntity(): void
    {
        $result = $this->availabiltyCollectionDataProvider->supports(Availability::class);
        self::assertTrue($result);
    }

    public function testDoesNotSupportWrongEntity(): void
    {
        $result = $this->availabiltyCollectionDataProvider->supports(stdClass::class);
        self::assertFalse($result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testGetCollectionReturnsNonEmptyGenerator(): void
    {
        $context['filters']['page'] = 1;

        $result = $this->availabiltyCollectionDataProvider->getCollection(Availability::class, null, $context);

        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self:self::assertNotEmpty($resultCollection);
    }
}
