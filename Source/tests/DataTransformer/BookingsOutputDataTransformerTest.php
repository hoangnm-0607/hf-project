<?php

namespace Tests\DataTransformer;

use App\DataTransformer\BookingsOutputDataTransformer;
use App\DataTransformer\Populator\Bookings\BookingsOutputPopulatorInterface;
use App\Dto\BookingOutputDto;
use App\Entity\Booking;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class BookingsOutputDataTransformerTest extends TestCase
{

    private BookingsOutputDataTransformer $bookingsOutputDataTransformer;

    private MockObject|BookingsOutputPopulatorInterface $populatorMock;

    protected function setUp(): void
    {
        $this->populatorMock                       = $this->createMock(BookingsOutputPopulatorInterface::class);
        $this->bookingsOutputDataTransformer = new BookingsOutputDataTransformer([$this->populatorMock]);
    }

    public function testSupportsTransformationBooking()
    {
        $supports = $this->bookingsOutputDataTransformer->supportsTransformation(new Booking(), BookingOutputDto::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->bookingsOutputDataTransformer->supportsTransformation(new stdClass(), BookingOutputDto::class);

        self::assertFalse($supports);
    }

    public function testTransform()
    {
        $data = new BookingOutputDto();
        $bookingMock = $this->createMock(Booking::class);

        $this->populatorMock->method('populate')->with($bookingMock)->willReturn($data);

        $result = $this->bookingsOutputDataTransformer->transform($bookingMock, BookingOutputDto::class);

        self::assertEquals($data, $result);
    }
}
