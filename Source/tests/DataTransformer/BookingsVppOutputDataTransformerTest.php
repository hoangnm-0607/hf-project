<?php

namespace Tests\DataTransformer;

use App\DataTransformer\BookingsVppOutputDataTransformer;
use App\DataTransformer\Populator\Bookings\BookingsVppOutputPopulatorInterface;
use App\Dto\VPP\Events\BookingDto;
use App\Entity\SingleEvent;
use App\Repository\BookingRepository;
use App\Service\SingleEventService;
use ArrayIterator;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Booking;
use Pimcore\Model\DataObject\Booking\Listing;
use Pimcore\Model\DataObject\CourseUser;
use stdClass;

class BookingsVppOutputDataTransformerTest extends TestCase
{
    private MockObject|BookingsVppOutputPopulatorInterface $populatorMock;
    private BookingRepository $bookingRepository;
    private BookingsVppOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populatorMock = $this->createMock(BookingsVppOutputPopulatorInterface::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $singleEventService = $this->createMock(SingleEventService::class);
        $this->dataTransformer = new BookingsVppOutputDataTransformer(
            [$this->populatorMock],
            $this->bookingRepository,
            $singleEventService
        );
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new SingleEvent(), BookingDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new SingleEvent(), stdClass::class);
        self::assertFalse($isSupports);
    }

    /**
     * @throws Exception
     */
    public function testTransform()
    {
        $object = new SingleEvent();

        $data = new BookingDto();
        $this->populatorMock->method('populate')->willReturn($data);

        $booking = new Booking();
        $booking->setUser(new CourseUser());
        $list = new Listing();
        $list->setData([
            $booking
        ]);
        $this->bookingRepository->method('getBookingsForEvent')->willReturn($list);

        $result = $this->dataTransformer->transform($object, BookingDto::class);

        self::assertEquals(new ArrayIterator([$data]), $result);
    }
}
