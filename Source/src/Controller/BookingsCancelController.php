<?php


namespace App\Controller;


use App\DataPersister\Helper\EventPersisterHelper;
use App\DataProvider\Helper\BookingProviderHelper;
use App\Exception\AlreadyCheckedInException;
use App\Exception\BookingNotSavedException;
use App\Exception\EventTimeConflictException;
use App\Exception\ObjectNotFoundException;
use App\Repository\BookingRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\SingleEventService;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingsCancelController
{

    private BookingProviderHelper $bookingHelper;
    private EventPersisterHelper $eventHelper;
    private BookingRepository $bookingRepository;
    private SingleEventService $singleEventService;
    private InMemoryUserValidator $inMemoryUserValidator;

    public function __construct(
        BookingProviderHelper $bookingHelper,
        EventPersisterHelper $eventHelper,
        BookingRepository $bookingRepository,
        SingleEventService $singleEventService,
        InMemoryUserValidator $inMemoryUserValidator
    ) {
        $this->bookingHelper = $bookingHelper;
        $this->eventHelper = $eventHelper;
        $this->bookingRepository = $bookingRepository;
        $this->singleEventService = $singleEventService;
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

            if ($booking->getCheckIn()) {
                throw new AlreadyCheckedInException('Can\'t cancel, because user has already checked in.');
            }

            $event = $booking->getEvent();
            if (Carbon::now()->addMinutes(15)->timestamp >= $this->singleEventService->getEventDateTimeTimestamp($event)) {
                throw new EventTimeConflictException('Events can only be cancelled until 15 minutes prior start.');
            }

            // check if user has already cancelled this booking. nevertheless, set usercancelled to true.
            $alreadyCancelled = $booking->getUserCancelled();
            $booking->setUserCancelled(true);

            if ($this->bookingHelper->save($booking)) {
                $event->setCapacity($event->getCapacity() + 1);

                $this->eventHelper->savePreparedEvent($event);

                $output = $this->bookingHelper->setBookingDto($booking);

                $statusCode = $alreadyCancelled ? Response::HTTP_ALREADY_REPORTED : Response::HTTP_OK;

                return new JsonResponse($output, $statusCode);
            } else {
                throw new BookingNotSavedException('Booking couldn\'t be saved');
            }
        } else {
            throw new ObjectNotFoundException('Booking ID not found');
        }
    }

}
