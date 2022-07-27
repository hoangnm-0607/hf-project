<?php

namespace Tests\DataTransformer\Populator\Bookings;

use App\DataPersister\Helper\BookingPersisterHelper;
use App\DataPersister\Helper\EventPersisterHelper;
use App\DataTransformer\Populator\Bookings\BookingsInputPopulator;
use App\Dto\BookingInputDto;
use App\Dto\CourseUserDto;
use App\Entity\Booking;
use App\Exception\AlreadyBookedException;
use App\Exception\MaximumCapacityReachedException;
use App\Repository\BookingRepository;
use App\Repository\SingleEventRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\CourseUserService;
use App\Service\SingleEventService;
use Carbon\Carbon;
use Exception;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\DataObject\SingleEvent;

class BookingsInputPopulatorTest extends TestCase
{
    /**
     * @var CourseUserService|mixed|MockObject
     */
    private CourseUserService|MockObject $courseUserServiceMock;
    private SingleEventService|MockObject $singleEventServiceMock;
    private SingleEventRepository|MockObject $singleEventRepositoryMock;
    private BookingRepository|MockObject $bookingRepositoryMock;
    private EventPersisterHelper|MockObject $eventPersisterHelperMock;
    private BookingPersisterHelper|MockObject $bookingPersisterHelperMock;
    private SingleEvent $event;
    private InMemoryUserValidator $inMemoryUserValidatorMock;
    private CourseUser $courseUser;

    public function setUp(): void
    {
        $this->courseUserServiceMock = $this->createMock(CourseUserService::class);
        $this->bookingPersisterHelperMock = $this->createMock(BookingPersisterHelper::class);
        $this->eventPersisterHelperMock = $this->createMock(EventPersisterHelper::class);
        $this->bookingRepositoryMock = $this->createMock(BookingRepository::class);
        $this->singleEventRepositoryMock = $this->createMock(SingleEventRepository::class);
        $this->singleEventServiceMock = $this->createMock(SingleEventService::class);
        $this->inMemoryUserValidatorMock = $this->createMock(InMemoryUserValidator::class);

        $this->courseUser = new CourseUser();
        $this->courseUser->setId(5555);

        $this->courseUserServiceMock
            ->method('getAndUpdateOrCreateCourseUser')
            ->willReturn($this->courseUser);

        $this->event = new SingleEvent();
        $this->event->setId(9494949);
        $this->event->setKey('YOGA');
        $this->event->setCourseDate(Carbon::now());


        $this->singleEventServiceMock
            ->method('getEventDateTimeTimestamp')
            ->with($this->event)
            ->willReturn(Carbon::now()->timestamp);

        $this->eventPersisterHelperMock
            ->method('savePreparedEvent')
            ->willReturn($this->event);
    }

    /**
     * @throws Exception
     */
    public function testPopulateNewUser(): void
    {
        $populator = new BookingsInputPopulator(
            $this->courseUserServiceMock,
            $this->bookingPersisterHelperMock,
            $this->eventPersisterHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );

        $input = $this->createInputForNewUser();

        $this->singleEventRepositoryMock
            ->method('getPublishedSingleEventByEventId')
            ->with($input->eventId)
            ->willReturn($this->event);

        $target = new Booking();

        $this->bookingPersisterHelperMock
            ->method('savePreparedBooking')
            ->willReturn($target);

        $output = $populator->populate($input, $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }

    /**
     * @throws Exception
     */
    public function testPopulateExistingUser(): void
    {
        $populator = new BookingsInputPopulator(
            $this->courseUserServiceMock,
            $this->bookingPersisterHelperMock,
            $this->eventPersisterHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );

        $input = $this->createInputForExistingUser();

        $this->singleEventRepositoryMock
            ->method('getPublishedSingleEventByEventId')
            ->with($input->eventId)
            ->willReturn($this->event);

        $target = new Booking();

        $this->bookingPersisterHelperMock
            ->method('savePreparedBooking')
            ->willReturn($target);

        $output = $populator->populate($input, $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }


    private function createInputForNewUser(): BookingInputDto
    {
        $this->courseUserServiceMock
            ->method('getAndUpdateOrCreateCourseUser')
            ->willReturn(null);

        $userDto         = new CourseUserDto();
        $userDto->userId = '5644644564';
        $userDto->firstName = 'Max';
        $userDto->lastName = 'Muster';
        $userDto->companyName = 'Hansefit';

        $input           = new BookingInputDto();
        $input->user     = $userDto;
        $input->eventId = 777;

        return $input;
    }

    #[Pure]
    private function createInputForExistingUser(): BookingInputDto
    {
        $userDto         = new CourseUserDto();
        $userDto->userId = '5644644564';
        $userDto->firstName = 'Max';
        $userDto->lastName = 'Muster';
        $userDto->companyName = 'Hansefit';

        $input           = new BookingInputDto();
        $input->user     = $userDto;
        $input->eventId = 777;

        return $input;
    }

    private function createExpectedOutput(): Booking
    {
        $output = new Booking();
        $output->setKey('YOGA-5555')
               ->setUser($this->courseUser)
               ->setPublished(true)
               ->setParent($this->event)
               ->setEvent($this->event)
               ->setBookingId('YOGA-'.$this->event->getId().'-'.$this->courseUser->getId());

        return $output;
    }

    /**
     * @throws MaximumCapacityReachedException
     */
    public function testExceptionsIsThrownWhenEventHasNoCapacity(): void
    {
        $populator = new BookingsInputPopulator(
            $this->courseUserServiceMock,
            $this->bookingPersisterHelperMock,
            $this->eventPersisterHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );

        $input = $this->createInputForExistingUser();

        $this->event->setCapacity(0);

        $this->singleEventRepositoryMock
            ->method('getPublishedSingleEventByEventId')
            ->with($input->eventId)
            ->willReturn($this->event);

        $this->expectException(MaximumCapacityReachedException::class);
        $populator->populate($input, new Booking());
    }

    /**
     * @throws MaximumCapacityReachedException
     */
    public function testExceptionWhenUserHasAlreadyBookedEvent():void
    {
        $populator = new BookingsInputPopulator(
            $this->courseUserServiceMock,
            $this->bookingPersisterHelperMock,
            $this->eventPersisterHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );

        $input = $this->createInputForExistingUser();

        $this->event->setCapacity(10);

        $this->singleEventRepositoryMock
            ->method('getPublishedSingleEventByEventId')
            ->with($input->eventId)
            ->willReturn($this->event);

        $this->bookingRepositoryMock
             ->method('getOneBookingByBookingId')
             ->willReturn(new Booking());

        $this->expectException(AlreadyBookedException::class);
        $populator->populate($input, new Booking());

    }

    /**
     * @throws MaximumCapacityReachedException
     */
    public function testNoExceptionWhenUserHasBookedButCancelledBeforehand(): void
    {
        $populator = new BookingsInputPopulator(
            $this->courseUserServiceMock,
            $this->bookingPersisterHelperMock,
            $this->eventPersisterHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );

        $input = $this->createInputForExistingUser();

        $this->event->setCapacity(10);

        $this->singleEventRepositoryMock
            ->method('getPublishedSingleEventByEventId')
            ->with($input->eventId)
            ->willReturn($this->event);

        $returnedBooking = new Booking();
        $returnedBooking->setUserCancelled(true);

        $this->bookingRepositoryMock
            ->method('getOneBookingByBookingId')
            ->willReturn($returnedBooking);

        $target = new Booking();

        $this->bookingPersisterHelperMock
            ->method('savePreparedBooking')
            ->willReturn($target);

        $output = $populator->populate($input, $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }
}
