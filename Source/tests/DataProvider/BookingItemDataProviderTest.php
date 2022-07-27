<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\BookingItemDataProvider;
use App\Entity\Booking;
use App\Repository\BookingRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class BookingItemDataProviderTest extends TestCase
{
    private BookingItemDataProvider $bookingItemDataProvider;
    private BookingRepository|MockObject $bookingRepository;

    protected function setUp(): void
    {
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $this->bookingItemDataProvider = new BookingItemDataProvider($this->bookingRepository);
    }

    public function testSupportsBookingEntity(): void
    {
        $result = $this->bookingItemDataProvider->supports(Booking::class);

        self::assertTrue($result);
    }

    public function testNotSupportsWrongEntity(): void
    {
        $result = $this->bookingItemDataProvider->supports(stdClass::class);

        self::assertFalse($result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testItemIsReturnedIfFound(): void
    {
        $booking = new Booking();

        $this->bookingRepository->method('getOneBookingByBookingId')->willReturn($booking);

        $result = $this->bookingItemDataProvider->getItem(Booking::class, 0);

        self::assertSame($booking, $result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testNullIsReturnedIfNotFound(): void
    {
        $result = $this->bookingItemDataProvider->getItem(Booking::class, 0);

        self::assertNull($result);
    }
}
