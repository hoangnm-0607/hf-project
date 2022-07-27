<?php


namespace App\DataTransformer\Populator\EventsVpp;


use App\Dto\VPP\Events\EventOutputDto;
use App\Exception\ObjectNotFoundException;
use App\Repository\BookingRepository;
use App\Service\I18NService;
use Carbon\Carbon;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\SingleEvent;

class EventsVppOutputPopulator implements EventsVppOutputPopulatorInterface
{

    private I18NService $i18NService;
    private BookingRepository $bookingRepository;

    public function __construct(I18NService $i18NService, BookingRepository $bookingRepository)
    {
        $this->i18NService = $i18NService;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function populate(SingleEvent $source, EventOutputDto $target): EventOutputDto
    {
        $target->eventId = $source->getId();
        $target->capacity = $source->getMaxCapacity();
        $target->bookings = $this->bookingRepository->getNumberBookingsForEvent($source);
        $target->checkins = $this->bookingRepository->getNumberCheckinsForEvent($source);
        $target->duration = $source->getDuration();
        $target->additionalInformation = $source->getAdditionalInformation();
        $target->cancelled = $source->getCancelled() ?? false;
        $target->eventId = $source->getId();
        $target->streamingHost = $source->getStreamingHost();
        $target->streamLink = $source->getStreamLink();
        $target->streamPassword = $source->getStreamPassword();
        $target->published = $source->isPublished();
        $target->meetingId = $source->getMeetingId();
        $target->timeZone = $source->getEnteredTimeZone();

        if ($source->getEnteredTimeZone() != date_default_timezone_get()) {
            $dateTime = new Carbon($source->getCourseDate()->toDateString() . ' ' . $source->getCourseStartTime() . ':00', date_default_timezone_get());
            $dateTime->setTimezone($source->getEnteredTimeZone());
            $target->date = $dateTime->format('Y-m-d');
            $target->time = $dateTime->format('H:i');
        }
        else {
            $target->date = $source->getCourseDate()->toDateString();
            $target->time = $source->getCourseStartTime();
        }

        $this->setCourseData($source, $target);

        return $target;
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function setCourseData(SingleEvent $source, EventOutputDto $target) {
        Course::setHideUnpublished(false);
        $course = $source->getParentCourse();
        Course::setHideUnpublished(true);
        if ($course == null) {
            throw new ObjectNotFoundException('Parent course for event '.$source->getId(). ' not found');
        }
        $target->courseName = $course->getCourseName($this->i18NService->getLanguageFromRequest());
        $target->courseType = $course->getCourseType();
        $target->exclusiveCourse = $course->getExclusiveCourse() ?? false;
        $target->internalCourseName = $course->getKey();
        $target->courseId = $course->getId();
    }
}
