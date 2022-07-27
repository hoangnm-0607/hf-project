<?php

namespace Tests\DataTransformer\Populator\Courses;

use App\DataTransformer\Populator\Courses\CoursesEventOutputPopulator;
use App\Dto\CoursesDto;
use App\Dto\EventDto;
use App\Entity\Booking;
use App\Entity\Courses;
use App\Repository\BookingRepository;
use App\Repository\CourseUserRepository;
use App\Service\SingleEventService;
use Carbon\Carbon;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\DataObject\SingleEvent;

class CoursesEventOutputPopulatorTest extends TestCase
{
    private SingleEventService|MockObject $singleEventServiceMock;
    private MockObject|CourseUserRepository $courseUserRepositoryMock;
    private MockObject|CourseUser $courseUserMock;
    private BookingRepository|MockObject $bookingRepository;

    public function setUp(): void
    {
        $this->courseUserRepositoryMock = $this->createMock(CourseUserRepository::class);
        $this->courseUserMock = $this->createMock(CourseUser::class);
        $this->bookingRepository = $this->createMock(BookingRepository::class);
        $this->singleEventServiceMock = $this->createMock(SingleEventService::class);

    }

    public function testPopulateWithoutUser()
    {

        $target = new CoursesDto();

        $courses = $this->createInput();
        $this->singleEventServiceMock->method('getEventDateTimeTimestamp')
                                        ->willReturn(1623967200);

        $populator = new CoursesEventOutputPopulator($this->courseUserRepositoryMock, $this->bookingRepository, $this->singleEventServiceMock);
        $output = $populator->populate($courses, $target, '');

        self::assertEquals($this->createExpectedOutputWithoutUser(), $output);

    }

    public function testPopulateWithUser()
    {
        $this->singleEventServiceMock->method('getEventDateTimeTimestamp')->willReturn(1623967200);
        $populator = new CoursesEventOutputPopulator($this->courseUserRepositoryMock, $this->bookingRepository, $this->singleEventServiceMock);
        $target = new CoursesDto();

        $event = new SingleEvent();
        $event->setCourseDate(new Carbon('2021-06-18'));
        $event->setDuration(90);
        $event->setAdditionalInformation('Hose nicht vergessen!');
        $event->setCancelled(false);
        $event->setId(666);
        $event->setStreamingHost('Zoom');
        $event->setStreamLink('https://zoom/link');
        $event->setStreamPassword('abc123');
        $event->setMeetingId('meeting444');

        $booking = new Booking();
        $booking->setBookingId(44);
        $booking->setEvent($event);
        $booking->setUserCancelled(true);

        $bookings = [$booking];

        $this->courseUserRepositoryMock
            ->method('getCourseUserByUserId')
            ->with('55225522')
            ->willReturn($this->courseUserMock);

        $this->bookingRepository
            ->method('getBookingListingByUser')
            ->with($this->courseUserMock)
            ->willReturn($bookings);


        $output = $populator->populate($this->createInput(), $target, '55225522');

        self::assertEquals($this->createExpectedOutputWithUser(), $output);
    }

    private function createInput(): Courses
    {
        $event1 = new SingleEvent();
        $event1->setCourseDate(new Carbon('2021-06-18'));
        $event1->setDuration(90);
        $event1->setAdditionalInformation('Hose nicht vergessen!');
        $event1->setCancelled(false);
        $event1->setId(666);
        $event1->setStreamingHost('Zoom');
        $event1->setStreamLink('https://zoom/link');
        $event1->setStreamPassword('abc123');
        $event1->setMeetingId('meeting444');
        $event1->setCapacity(22);

        $event2 = new SingleEvent();
        $event2->setCourseDate(new Carbon('2021-07-18'));
        $event2->setDuration(45);
        $event2->setAdditionalInformation('T-Shirt nicht vergessen!');
        $event2->setCancelled(true);
        $event2->setId(888);
        $event2->setStreamingHost('TikTok');
        $event2->setStreamLink('https://tik/tok/link');
        $event2->setStreamPassword('123abc');
        $event2->setMeetingId('444meeting');
        $event2->setCapacity(0);

        $input = new Courses();
        $input->setSingleEvents([$event1,$event2]);

        return $input;

    }

    #[Pure] private function createExpectedOutputWithoutUser(): CoursesDto
    {
        $eventOutput1 = new EventDto();
        $eventOutput1->courseDate = 1623967200;
        $eventOutput1->duration = 90;
        $eventOutput1->additionalInformation = 'Hose nicht vergessen!';
        $eventOutput1->cancelled = false;
        $eventOutput1->eventId = 666;
        $eventOutput1->streamingHost = 'Zoom';
        $eventOutput1->streamLink = 'https://zoom/link';
        $eventOutput1->streamPassword = 'abc123';
        $eventOutput1->meetingId = 'meeting444';
        $eventOutput1->fullybooked = false;

        $eventOutput2 = new EventDto();
        $eventOutput2->courseDate = 1623967200;
        $eventOutput2->duration = 45;
        $eventOutput2->additionalInformation = 'T-Shirt nicht vergessen!';
        $eventOutput2->cancelled = true;
        $eventOutput2->eventId = 888;
        $eventOutput2->streamingHost = 'TikTok';
        $eventOutput2->streamLink = 'https://tik/tok/link';
        $eventOutput2->streamPassword = '123abc';
        $eventOutput2->meetingId = '444meeting';
        $eventOutput2->fullybooked = true;

        $courseOutput = new CoursesDto();
        $courseOutput->events = [$eventOutput1,$eventOutput2];

        return $courseOutput;
    }

    #[Pure] private function createExpectedOutputWithUser(): CoursesDto
    {
        $eventOutput1 = new EventDto();
        $eventOutput1->courseDate = 1623967200;
        $eventOutput1->duration = 90;
        $eventOutput1->additionalInformation = 'Hose nicht vergessen!';
        $eventOutput1->cancelled = false;
        $eventOutput1->eventId = 666;
        $eventOutput1->streamingHost = 'Zoom';
        $eventOutput1->streamLink = 'https://zoom/link';
        $eventOutput1->streamPassword = 'abc123';
        $eventOutput1->meetingId = 'meeting444';
        $eventOutput1->userCancelled = true;
        $eventOutput1->bookingId = 44;

        $courseOutput = new CoursesDto();
        $courseOutput->events = [$eventOutput1];

        return $courseOutput;
    }
}
