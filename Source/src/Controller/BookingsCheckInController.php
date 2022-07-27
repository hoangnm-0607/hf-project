<?php


namespace App\Controller;


use App\DataProvider\Helper\BookingProviderHelper;
use App\Exception\AlreadyCancelledException;
use App\Exception\BookingNotSavedException;
use App\Exception\ObjectNotFoundException;
use App\Repository\BookingRepository;
use App\Security\Validator\InMemoryUserValidator;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingsCheckInController
{

    private BookingProviderHelper $bookingHelper;
    private BookingRepository $bookingRepository;
    private InMemoryUserValidator $inMemoryUserValidator;

    public function __construct( BookingProviderHelper $bookingHelper, BookingRepository $bookingRepository, InMemoryUserValidator $inMemoryUserValidator)
    {
        $this->bookingHelper = $bookingHelper;
        $this->bookingRepository = $bookingRepository;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request): ?JsonResponse
    {
        if (($data = json_decode($request->getContent(), true)) &&
            ($bookingId = $data['bookingId']) &&
            ($booking = $this->bookingRepository->getOneBookingByBookingId($bookingId))) {

            $this->inMemoryUserValidator->validateTokenAndApiUserId($booking->getUser()->getUserId());

            if ($booking->getUserCancelled()) {
                throw new AlreadyCancelledException('Can\'t check in, because user has cancelled the booking.');
            }

            $alreadyCheckedIn = $booking->getCheckIn();
            $booking->setCheckIn(new Carbon('now'));
            if ($this->bookingHelper->save($booking)) {
                $output = $this->bookingHelper->setBookingDto($booking);

                $statusCode = $alreadyCheckedIn ? Response::HTTP_ALREADY_REPORTED : Response::HTTP_OK;
                return new JsonResponse($output, $statusCode);

            } else {
                throw new BookingNotSavedException('Booking couldn\'t be saved');
            }
        } else {
            throw new ObjectNotFoundException('Booking ID not found');
        }
    }

}
