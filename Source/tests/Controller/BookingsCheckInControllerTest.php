<?php

namespace Tests\Controller;

use App\Controller\BookingsCheckInController;
use App\DataProvider\Helper\BookingProviderHelper;
use App\Dto\BookingOutputDto;
use App\Entity\Booking;
use App\Exception\AlreadyCancelledException;
use App\Exception\ObjectNotFoundException;
use App\Repository\BookingRepository;
use App\Security\Validator\InMemoryUserValidator;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\CourseUser;
use Symfony\Component\HttpFoundation\Request;

class BookingsCheckInControllerTest extends TestCase
{

    private InMemoryUserValidator|MockObject $inMemoryUserValidatorMock;
    private Request|MockObject $requestMock;
    private BookingRepository|MockObject $bookingRepositoryMock;
    private MockObject|Booking $bookingMock;
    private MockObject|BookingProviderHelper $bookingHelperMock;

    protected function setUp(): void
    {
        $this->bookingHelperMock = $this->createMock(BookingProviderHelper::class);
        $this->bookingRepositoryMock = $this->createMock(BookingRepository::class);
        $this->inMemoryUserValidatorMock = $this->createMock(InMemoryUserValidator::class);
        $this->requestMock = $this->createMock(Request::class);

        $courseUser = new CourseUser();
        $courseUser->setUserId('12345');

        $this->bookingMock = $this->createMock(Booking::class);
        $this->bookingMock->method('getUser')->willReturn($courseUser);
    }

    /**
     * @throws Exception
     */
    public function testBookingCallsSetBookingDto()
    {
        $data['bookingId'] = '12345';

        $bookingOutputDto = new BookingOutputDto();
        $bookingOutputDto->eventId = '54321';
        $bookingOutputDto->bookingId = '12345';

        $this->requestMock->method('getContent')->willReturn(json_encode($data));

        $this->bookingRepositoryMock->method('getOneBookingByBookingId')->with('12345')->willReturn($this->bookingMock);
        $this->bookingHelperMock->method('save')->with($this->bookingMock)->willReturn($this->bookingMock);
        $this->bookingHelperMock->method('setBookingDto')->with($this->bookingMock)->willReturn($bookingOutputDto);

        $bookingsCheckinController = new BookingsCheckInController($this->bookingHelperMock, $this->bookingRepositoryMock, $this->inMemoryUserValidatorMock);

        $result = $bookingsCheckinController($this->requestMock);

        self::assertEquals(json_encode($bookingOutputDto), $result->getContent());

    }

    /**
     * @throws Exception
     */
    public function testCancelExceptionOnEmptyBookingId(): void
    {
        $bookingsCheckinController = new BookingsCheckInController($this->bookingHelperMock, $this->bookingRepositoryMock, $this->inMemoryUserValidatorMock);
        $this->requestMock->method('getContent')->willReturn(json_encode([]));

        $this->expectException(ObjectNotFoundException::class);

        $bookingsCheckinController($this->requestMock);
    }

    /**
     * @throws Exception
     */
    public function testExceptionIsThrownIfAlreadyCancelled(): void
    {
        $data['bookingId'] = '12345';

        $this->requestMock->method('getContent')->willReturn(json_encode($data));
        $this->bookingRepositoryMock->method('getOneBookingByBookingId')->with('12345')->willReturn($this->bookingMock);

        $this->bookingMock->method('getUserCancelled')->willReturn(true);

        $this->expectException(AlreadyCancelledException::class);

        $bookingsCheckinController = new BookingsCheckInController($this->bookingHelperMock, $this->bookingRepositoryMock, $this->inMemoryUserValidatorMock);
        $bookingsCheckinController($this->requestMock);
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

        $bookingsCheckinController = new BookingsCheckInController($this->bookingHelperMock, $this->bookingRepositoryMock, $this->inMemoryUserValidatorMock);
        $bookingsCheckinController($this->requestMock);
    }
}
