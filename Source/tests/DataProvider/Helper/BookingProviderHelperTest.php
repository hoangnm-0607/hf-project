<?php

namespace Tests\DataProvider\Helper;

use App\DataProvider\Helper\BookingProviderHelper;
use App\Dto\BookingOutputDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Booking;
use Pimcore\Model\DataObject\SingleEvent;

class BookingProviderHelperTest extends TestCase
{
    protected Booking|MockObject $bookingMock;
    protected BookingProviderHelper $bookingProviderHelper;
    protected MockObject|SingleEvent $eventMock;

    protected function setUp(): void
    {
        $this->bookingMock = $this->createMock(Booking::class);
        $this->bookingProviderHelper = new BookingProviderHelper();
        $this->eventMock = $this->createMock(SingleEvent::class);
    }

    /**
     * @test
     */
    public function SetBookingDto_ReturnsMatchingBookingOutputDto()
    {
        $this->eventMock->method('getId')->willReturn(444);
        $this->bookingMock->method('getEvent')->willReturn($this->eventMock);
        $this->bookingMock->method('getBookingId')->willReturn(555);

        $expected = new BookingOutputDto();
        $expected->bookingId = '555';
        $expected->eventId = '444';

        $output = $this->bookingProviderHelper->setBookingDto($this->bookingMock);

        self::assertEquals($expected, $output);
    }
    /**
     * @test
     */
    public function SetBookingDto_FailsToReturnMatchingBookingOutputDto()
    {
        $this->eventMock->method('getId')->willReturn(444);
        $this->bookingMock->method('getEvent')->willReturn($this->eventMock);
        $this->bookingMock->method('getBookingId')->willReturn(555);

        $expected = new BookingOutputDto();
        $expected->bookingId = '222';
        $expected->eventId = '333';

        $output = $this->bookingProviderHelper->setBookingDto($this->bookingMock);

        self::assertNotEquals($expected, $output);
    }
}
