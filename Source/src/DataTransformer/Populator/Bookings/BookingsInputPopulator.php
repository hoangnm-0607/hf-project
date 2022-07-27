<?php


namespace App\DataTransformer\Populator\Bookings;


use App\DataPersister\Helper\BookingPersisterHelper;
use App\DataPersister\Helper\EventPersisterHelper;
use App\Dto\BookingInputDto;
use App\Entity\Booking;
use App\Exception\AlreadyBookedException;
use App\Exception\EventTimeConflictException;
use App\Exception\MaximumCapacityReachedException;
use App\Exception\ObjectNotFoundException;
use App\Repository\BookingRepository;
use App\Repository\SingleEventRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\CourseUserService;
use App\Service\SingleEventService;
use Carbon\Carbon;
use Exception;
use Pimcore\Model\DataObject\CourseUser;
use Pimcore\Model\DataObject\Data\ObjectMetadata;
use Pimcore\Model\DataObject\SingleEvent;

class BookingsInputPopulator implements BookingsInputPopulatorInterface
{
    private BookingPersisterHelper $bookingPersisterHelper;
    private EventPersisterHelper $eventPersisterHelper;
    private BookingRepository $bookingRepository;
    private SingleEventRepository $singleEventRepository;
    private SingleEventService $singleEventService;
    private InMemoryUserValidator $inMemoryUserValidator;

    private bool $updateEvent = false;
    private CourseUserService $courseUserService;

    public function __construct(
        CourseUserService $courseUserService,
        BookingPersisterHelper $bookingPersisterHelper,
        EventPersisterHelper $eventPersisterHelper,
        BookingRepository $bookingRepository,
        SingleEventRepository $singleEventRepository,
        SingleEventService $singleEventService,
        InMemoryUserValidator $inMemoryUserValidator
    )
    {
        $this->bookingPersisterHelper = $bookingPersisterHelper;
        $this->eventPersisterHelper = $eventPersisterHelper;
        $this->bookingRepository = $bookingRepository;
        $this->singleEventRepository = $singleEventRepository;
        $this->singleEventService = $singleEventService;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->courseUserService = $courseUserService;
    }

    /**
     * @throws MaximumCapacityReachedException|Exception
     */
    public function populate(BookingInputDto $source, Booking $target): Booking
    {
        if ($event = $this->singleEventRepository->getPublishedSingleEventByEventId($source->eventId)) {
            $eventCapacity = $event->getCapacity();
            if($eventCapacity === 0) {
                throw new MaximumCapacityReachedException('Maximum course capacity reached');
            }

            // Booking is possible until 10 minutes after course start
            if ($this->singleEventService->getEventDateTimeTimestamp($event) <= Carbon::now()->subMinutes(10)->timestamp) {
                throw new EventTimeConflictException('Events can only be booked until 10 minutes after start.');
            }
            // Booking is possible from 29 days 00:00 h before course start
            if (Carbon::createFromTimestamp($this->singleEventService->getEventDateTimeTimestamp($event))->startOfDay() > Carbon::now()->addDays(29)) {
                throw new EventTimeConflictException('Events can only be booked 29 days before start.');
            }

            if($userId = $source->user->userId) {

                $this->inMemoryUserValidator->validateTokenAndApiUserId($userId);

                if ($courseUser = $this->courseUserService->getAndUpdateOrCreateCourseUser(
                    [
                        'userId' => $source->user->userId,
                        'firstname' => $source->user->firstName,
                        'lastname' => $source->user->lastName,
                        'company' => $source->user->companyName,
                    ])) {
                    $target = $this->prepareBooking($event, $courseUser, $target);
                    $this->saveBookingAndSetEvent($target, $event, $eventCapacity, $courseUser);
                }
            }
        } else {
            throw new ObjectNotFoundException('There is no event with this id.');
        }

        return $target;
    }

    /**
     * @throws AlreadyBookedException
     * TODO: move to bookingPersisterHelper + refactor
     */
    private function prepareBooking(?SingleEvent $event, CourseUser $courseUser, Booking $target): Booking
    {
        $bookingId = str_replace(' ', '_', $event->getKey()) . '-' . $event->getId() . '-' . $courseUser->getId();
        // If there is already a booking stop and throw exception.
        // PIM-126: If a user has cancelled a previous booking of this event, new bookings are allowed.
        if ($booking = $this->bookingRepository->getOneBookingByBookingId($bookingId)) {
            $this->updateEvent = true;
            if (!$booking->getUserCancelled()) {
                throw new AlreadyBookedException('You have already booked this event! ('. $bookingId . ')');
            } else {
                // if we're getting here and have an existing booking object that was cancelled, overwrite our target with this
                // object. Then make sure parent and key are not newly set.
                $target = $booking;
            }
        }

        // If our target has no parent we can be pretty sure that we're using a new object and not an existing booking.
        if(!$target->getParent()) {
            $target->setParent($event);
            $target->setKey($event->getKey() . '-' . $courseUser->getId());
        }

        $target->setUser($courseUser);
        $target->setEvent($event);
        $target->setBookingId($bookingId);
        $target->setPublished(true);
        // PIM-126: set cancelled status to false
        $target->setUserCancelled(false);

        return $target;
    }

    /**
     * @throws Exception
     * TODO: move to bookingPersisterHelper
     */
    private function saveBookingAndSetEvent(Booking $target, ?SingleEvent $event, ?float $eventCapacity, CourseUser $courseUser): void
    {
        if ($this->bookingPersisterHelper->savePreparedBooking($target)) {
            if (!$this->updateEvent) {
                $bookingMetaData = new ObjectMetadata('Bookings', ['Firstname', 'Lastname', 'Company'], $target);
                $bookingMetaData->setFirstname($courseUser->getFirstname());
                $bookingMetaData->setLastname($courseUser->getLastname());
                $bookingMetaData->setCompany($courseUser->getCompany());

                $event->setBookings(array_merge($event->getBookings(), [$bookingMetaData]));
            }
            $event->setCapacity($eventCapacity - 1);

            $this->eventPersisterHelper->savePreparedEvent($event);
        }
    }
}
