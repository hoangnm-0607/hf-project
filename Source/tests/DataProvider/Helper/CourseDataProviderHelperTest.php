<?php

namespace Tests\DataProvider\Helper;

use App\DataProvider\Helper\CourseDataProviderHelper;
use App\Repository\BookingRepository;
use App\Repository\CourseUserRepository;
use App\Service\DataObjectService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Booking;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\DataObject\SingleEvent;

final class CourseDataProviderHelperTest extends TestCase
{
    private BookingRepository|MockObject $bookingRepositoryMock;
    private CourseDataProviderHelper $courseDataProviderHelper;
    private DataObjectService|MockObject $dataObjectServiceMock;
    private MockObject|CourseUser $courseUserMock;

    protected function setUp(): void
    {
        $this->courseUserMock = $this->createMock(CourseUser::class);
//        $this->courseUserMock->method('getId')->willReturn('123');
//        $this->courseUserMock->method('getType')->willReturn('object');
        $courseUserRepositoryMock = $this->createMock(CourseUserRepository::class);
        $courseUserRepositoryMock->method('getCourseUserByUserId')->willReturn($this->courseUserMock);
        $this->bookingRepositoryMock = $this->createMock(BookingRepository::class);

        $this->courseDataProviderHelper = new CourseDataProviderHelper($courseUserRepositoryMock, $this->bookingRepositoryMock);

    }

    /**
     * @test
     */
    public function GetCourseIdsOfBookedEvents_ReturnsEmptyArray()
    {
        $output = $this->courseDataProviderHelper->getCourseIdsOfBookedEvents('123');

        self::assertEmpty($output);
    }

    /**
     * @test
     */
    public function GetCourseIdsOfBookedEvents_ReturnsCertainArray()
    {
        $course1 = $this->createMock(Course::class);
        $course1->method('getId')->willReturn(123);
        $booking1 = $this->createMock(Booking::class);
        $event1 = $this->createMock(SingleEvent::class);
        $event1->method('getParentCourse')->willReturn($course1);
        $booking1->method('getEvent')->willReturn($event1);

        $course2 = $this->createMock(Course::class);
        $course2->method('getId')->willReturn(321);
        $booking2 = $this->createMock(Booking::class);
        $event2 = $this->createMock(SingleEvent::class);
        $event2->method('getParentCourse')->willReturn($course2);

        $booking2->method('getEvent')->willReturn($event2);

        $bookings = [$booking1, $booking2];

        $this->bookingRepositoryMock->method('getBookingListingByUser')->with($this->courseUserMock)->willReturn($bookings);
        $output = $this->courseDataProviderHelper->getCourseIdsOfBookedEvents('123');

        self::assertEquals([123,321], $output);
    }

    /**
     * @test
     */
    public function GetCourseIdsOfBookedEvents_FailsToReturnCertainArray()
    {
        $course1 = $this->createMock(Course::class);
        $course1->method('getId')->willReturn(123);
        $booking1 = $this->createMock(Booking::class);
        $event1 = $this->createMock(SingleEvent::class);
        $booking1->method('getEvent')->willReturn($event1);

        $course2 = $this->createMock(Course::class);
        $course2->method('getId')->willReturn(321);
        $booking2 = $this->createMock(Booking::class);
        $event2 = $this->createMock(SingleEvent::class);
        $booking2->method('getEvent')->willReturn($event2);

        $bookings = [$booking1, $booking2];

        $this->bookingRepositoryMock->method('getBookingListingByUser')->with($this->courseUserMock)->willReturn($bookings);
        $output = $this->courseDataProviderHelper->getCourseIdsOfBookedEvents('123');

        self::assertNotEquals([555,321], $output);
    }
}
