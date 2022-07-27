<?php

namespace Tests\DataTransformer;

use App\DataTransformer\BookingsInputDataTransformer;
use App\DataTransformer\Populator\Bookings\BookingsInputPopulatorInterface;
use App\Dto\BookingInputDto;
use App\Entity\Booking;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class BookingsInputDataTransformerTest extends TestCase
{

    private BookingsInputDataTransformer $bookingsInputDataTransformer;

    private BookingsInputPopulatorInterface|MockObject $populatorMock;

    protected function setUp(): void
    {
        $this->populatorMock                = $this->createMock(BookingsInputPopulatorInterface::class);
        $this->bookingsInputDataTransformer = new BookingsInputDataTransformer([$this->populatorMock]);
    }

    public function testSupportsTransformationBookingDto()
    {
        $supports = $this->bookingsInputDataTransformer->supportsTransformation(new BookingInputDto(), Booking::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongDto():void
    {
        $supports = $this->bookingsInputDataTransformer->supportsTransformation(new stdClass(), Booking::class);

        self::assertFalse($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->bookingsInputDataTransformer->supportsTransformation(new Booking(), Booking::class);

        self::assertFalse($supports);
    }

    public function testTransform()
    {
        $data = new Booking();
        $bookingDtoMock = $this->createMock(BookingInputDto::class);

        $this->populatorMock->method('populate')->with($bookingDtoMock)->willReturn($data);

        $result = $this->bookingsInputDataTransformer->transform($bookingDtoMock, Booking::class);

        self::assertEquals($data, $result);
    }
}
