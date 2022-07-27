<?php


namespace App\DataTransformer\Populator\Courses;


use App\Dto\CoursesDto;
use App\Dto\EventDto;
use App\Entity\Courses;
use App\Repository\BookingRepository;
use App\Repository\CourseUserRepository;
use App\Service\SingleEventService;
use Pimcore\Model\DataObject\SingleEvent;

class CoursesEventOutputPopulator implements CoursesOutputPopulatorInterface
{
    private BookingRepository $bookingRepository;
    private CourseUserRepository $courseUserRepository;
    private SingleEventService $singleEventService;

    public function __construct(CourseUserRepository $courseUserRepository, BookingRepository $bookingRepository, SingleEventService $singleEventService)
    {
        $this->bookingRepository = $bookingRepository;
        $this->courseUserRepository = $courseUserRepository;
        $this->singleEventService = $singleEventService;
    }

    public function populate(Courses $source, CoursesDto $target, ?string $userId): CoursesDto
    {
        $events = $source->getSingleEvents();
        $bookedEvents = [];

        if ($userId) {
            $courseUser = $this->courseUserRepository->getCourseUserByUserId($userId);
            $bookings   = $this->bookingRepository->getBookingListingByUser($courseUser);

            // TODO: is this really the best way to get the events/bookings?
            foreach ($bookings as $booking) {
                SingleEvent::setHideUnpublished(false);
                $bookedEvents[$booking->getEvent()->getId()] = $booking;
                SingleEvent::setHideUnpublished(true);
            }
        }

        foreach ($events as $event) {
            // if we have a userId but the event is not in our bookedEvents list, disregard the event
            if($userId && !in_array($event->getId(), array_keys($bookedEvents))) {
                continue;
            }
            $eventDto = new EventDto();
            $eventDto->courseDate = $this->singleEventService->getEventDateTimeTimestamp($event);
            $eventDto->duration = $event->getDuration();
            $eventDto->additionalInformation = $event->getAdditionalInformation();
            $eventDto->meetingId = $event->getMeetingId();
            $eventDto->cancelled = $event->getCancelled() ?? false;
            $eventDto->eventId = $event->getId();
            $eventDto->streamingHost = $event->getStreamingHost();
            $eventDto->streamLink = $event->getStreamLink();
            $eventDto->streamPassword = $event->getStreamPassword();
            $eventDto->fullybooked = ($event->getCapacity() <= 0);

            if($bookedEvents) {
                $eventDto->userCancelled = $bookedEvents[$event->getId()]->getUserCancelled() ?? false;
                $eventDto->bookingId     = $bookedEvents[$event->getId()]->getBookingId();
            }

            $target->events[] = $eventDto;
        }
        return $target;
    }
}
