<?php

namespace Tests\Controller;

use App\Controller\BookingsCancelController;
use App\DataPersister\Helper\EventPersisterHelper;
use App\DataProvider\Helper\BookingProviderHelper;
use App\Dto\BookingOutputDto;
use App\Entity\Booking;
use App\Exception\AlreadyCheckedInException;
use App\Exception\ObjectNotFoundException;
use App\Repository\BookingRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\SingleEventService;
use Carbon\Carbon;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\DataObject\SingleEvent;
use Symfony\Component\HttpFoundation\Request;

class BookingsCancelControllerTest extends TestCase
{
    protected Request|MockObject $requestMock;
    private InMemoryUserValidator|MockObject $inMemoryUserValidatorMock;
    private SingleEventService|MockObject $singleEventServiceMock;
    private BookingRepository|MockObject $bookingRepositoryMock;
    private EventPersisterHelper|MockObject $eventHelperMock;
    private MockObject|Booking $bookingMock;
    private MockObject|BookingProviderHelper $bookingHelperMock;

    protected function setUp(): void
    {
        $this->bookingHelperMock     = $this->createMock(BookingProviderHelper::class);
        $this->bookingRepositoryMock = $this->createMock(BookingRepository::class);
        $this->requestMock           = $this->createMock(Request::class);
        $this->bookingMock = $this->createMock(Booking::class);
        $this->eventHelperMock = $this->createMock(EventPersisterHelper::class);
        $this->singleEventServiceMock = $this->createMock(SingleEventService::class);
        $this->inMemoryUserValidatorMock = $this->createMock(InMemoryUserValidator::class);
    }

    /**
     * @throws Exception
     */
    public function testCancelledBookingIncreasesEventCapacity()
    {
        $data['bookingId'] = '12345';

        $expect = new BookingOutputDto();
        $expect->eventId = '54321';
        $expect->bookingId = '12345';

        $event = new SingleEvent();
        $event->setCapacity(10);
        $event->setCancelled(false);
        $event->setCourseDate(Carbon::now()->addMinutes(20));

        $user = new CourseUser();
        $user->setUserId(123);

        $this->singleEventServiceMock->method('getEventDateTimeTimestamp')->with($event)->willReturn(Carbon::now()->addMinutes(20)->timestamp);

        $this->requestMock->method('getContent')->willReturn(json_encode($data));

        $this->bookingRepositoryMock->method('getOneBookingByBookingId')->with('12345')->willReturn($this->bookingMock);
        $this->bookingHelperMock->method('save')->with($this->bookingMock)->willReturn($this->bookingMock);
        $this->bookingMock->method('getEvent')->willReturn($event);
        $this->bookingMock->method('getUser')->willReturn($user);

        $this->bookingHelperMock->method('setBookingDto')->with($this->bookingMock)->willReturn($expect);
        $this->eventHelperMock->method('savePreparedEvent')->with($event)->willReturn($event);

        $bookingsCancelController = new BookingsCancelController(
            $this->bookingHelperMock,
            $this->eventHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );

        $bookingsCancelController($this->requestMock);

        self::assertEquals(11, $event->getCapacity());

    }

    /**
     * @throws Exception
     */
    public function testExceptionOnEmptyBookingId(): void
    {
        $bookingsCancelController = new BookingsCancelController(
            $this->bookingHelperMock,
            $this->eventHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );
        $this->requestMock->method('getContent')->willReturn(json_encode([]));
        $this->expectException(ObjectNotFoundException::class);
        $bookingsCancelController($this->requestMock);
    }

    /**
     * @throws Exception
     */
    public function testExceptionIsThrownWhenAlreadyCheckedIn():void
    {
        $data['bookingId'] = '12345';

        $this->requestMock->method('getContent')->willReturn(json_encode($data));
        $this->bookingRepositoryMock->method('getOneBookingByBookingId')->with('12345')->willReturn($this->bookingMock);

        $this->bookingMock->method('getCheckIn')->willReturn(new Carbon());
        $user = new CourseUser();
        $user->setUserId('12345');
        $this->bookingMock->method('getUser')->willReturn($user);

        $this->expectException(AlreadyCheckedInException::class);

        $bookingsCancelController = new BookingsCancelController(
            $this->bookingHelperMock,
            $this->eventHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );
        $bookingsCancelController($this->requestMock);
    }


    /**
     * @throws Exception
     */
    public function testExceptionIfBookingIDNotFound(): void
    {
        $data['bookingId'] = '12345';

        $this->requestMock->method('getContent')->willReturn(json_encode($data));
        $this->bookingRepositoryMock->method('getOneBookingByBookingId')->with('12345')->willReturn(null);

        $this->expectException(ObjectNotFoundException::class);

        $bookingsCancelController = new BookingsCancelController(
            $this->bookingHelperMock,
            $this->eventHelperMock,
            $this->bookingRepositoryMock,
            $this->singleEventServiceMock,
            $this->inMemoryUserValidatorMock
        );
        $bookingsCancelController($this->requestMock);
    }
}
