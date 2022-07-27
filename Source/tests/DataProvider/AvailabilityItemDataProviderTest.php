<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\AvailabilityItemDataProvider;
use App\Entity\Availability;
use App\Repository\SingleEventRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\SingleEvent;
use stdClass;

class AvailabilityItemDataProviderTest extends TestCase
{
    private AvailabilityItemDataProvider $availabilityItemDataProvider;
    private SingleEventRepository|MockObject $singleEventRepository;

    protected function setUp(): void
    {
        $this->singleEventRepository = $this->createMock(SingleEventRepository::class);
        $this->availabilityItemDataProvider = new AvailabilityItemDataProvider($this->singleEventRepository);
    }

    public function testSupportsAvailabilityEntity(): void
    {
        $result = $this->availabilityItemDataProvider->supports(Availability::class);

        self::assertTrue($result);
    }

    public function testNotSupportsWrongEntity(): void
    {
        $result = $this->availabilityItemDataProvider->supports(stdClass::class);

        self::assertFalse($result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testItemIsReturnedIfFound(): void
    {
        $availability = new SingleEvent();

        $this->singleEventRepository->method('getOneSingleEventById')->willReturn($availability);

        $result = $this->availabilityItemDataProvider->getItem(SingleEvent::class, 0);

        self::assertSame($availability, $result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testNullIsReturnedIfNotFound(): void
    {
        $result = $this->availabilityItemDataProvider->getItem(SingleEvent::class, 0);

        self::assertNull($result);
    }
}
