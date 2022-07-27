<?php


namespace App\Service;


use App\DataCollection\EventErrorCollection;
use App\Entity\SingleEvent;
use App\Repository\SingleEventRepository;
use ArrayIterator;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Pimcore\Model\DataObject\Course;

class CourseGeneratorService
{
    private SingleEventRepository $singleEventRepository;
    private SingleEventService $singleEventService;

    public function __construct(SingleEventRepository $singleEventRepository, SingleEventService $singleEventService)
    {
        $this->singleEventRepository = $singleEventRepository;
        $this->singleEventService = $singleEventService;
    }

    /**
     * Takes a repetition value and a course object and generates a
     * list of dates for the given values inside the course object.
     *
     * @param string|null $repetition
     * @param array|null  $weekdays
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     *
     * @return array|CarbonPeriod
     */
    public function getEventSeriesDates(?string $repetition, ?array $weekdays, ?Carbon $startDate, ?Carbon $endDate): array|CarbonPeriod
    {
        if ($repetition > 0) {
            $weekdays    = array_map('intval', array_values($weekdays));
            $periodicity = [
                'days'        => $weekdays,
                'repeatWeeks' => $repetition,
            ];

            $dateList = $this->generateCourseDatesByWeekdays(
                $periodicity,
                $startDate,
                $endDate
            );
        } else {
            $dateList = [$startDate];
        }

        return $dateList;
    }

    /**
     * This method generates a CarbonPeriod object, with a list of dates matching the given conditions
     *
     * @param array  $periodicity
     * @param Carbon $entry_start
     * @param Carbon $periodicity_end
     *
     * @return CarbonPeriod
     */
    public function generateCourseDatesByWeekdays(array $periodicity, Carbon $entry_start, Carbon $periodicity_end): CarbonPeriod
    {
        /**
         * monday, tuesday, wednesday
         * @example : [1,2,3]
         */
        $days = $periodicity['days'];
        /**
         * @example 1 for every week, 2 every 2 weeks, 3,4...
         */
        $repeat_week = $periodicity['repeatWeeks'];

        /**
         * filter days of the week
         *
         * @param $date
         *
         * @return bool
         */
        $filterDayOfWeek = function ($date) use ($days) {
            return in_array($date->dayOfWeekIso, $days, true);
        };

        /**
         * Carbon::class
         * $this->entry_start
         * $this->periodicity_end
         */
        $period = Carbon::parse($entry_start->toDateTimeString())
                        ->daysUntil(
                            $periodicity_end->toDateTimeString()
                        );

        /**
         * filter every x weeks
         *
         * @param $date
         *
         * @return bool
         */
        $filterWeek = function ($date) use ($repeat_week) {
            return $date->weekOfYear % $repeat_week === 0;
        };

        $period->addFilter($filterDayOfWeek);

        if ($repeat_week > 1) {
            $period->addFilter($filterWeek);
        }

        return $period;
    }

    /**
     * Takes a list of dates and loads or generates a collection of event objects
     * @throws Exception
     */
    public function generateNewEventCollection($dateList, Course $course, $defaultValues = [], $streamValues = [], $publish = false): EventErrorCollection
    {
        $eventCollection = new ArrayIterator();

        $startTime       = $defaultValues['startTime'] ?? $course->getCourseStartTime();
        $duration        = $defaultValues['duration'] ?? $course->getDuration();
        $capacity        = $defaultValues['capacity'] ?? $course->getCapacity();
        $enteredTimeZone = $defaultValues['enteredTimeZone'] ?? date_default_timezone_get();
        $courseId        = $course->getCourseID();
        $partner         = $course->getPartnerProfile();

        $failedDates = [];
        foreach ($dateList as $date) {
            $date->setTimeFromTimeString($startTime . ':00');

            // check if event date is earlier than the partner's start date
            if ($partner->getStartDate() != null && $date < $partner->getStartDate()) {
                $failedDates[] = $date->format('Y-m-d H:i');
                continue;
            }

            // check if event date is later than the partner's termination date
            if ($partner->getTerminationDate() != null && $date > $partner->getTerminationDate()->clone()->setTime(23,59,59)) {
                $failedDates[] = $date->format('Y-m-d H:i');
                continue;
            }

            if ($this->singleEventService->checkIfEventExistByDateTime($date, $courseId)) {
                $failedDates[] = $date->format('Y-m-d H:i');
                continue;
            }

            $eventKey = $this->singleEventService->generateEventKey($courseId, $date);

            $event = $this->getOrCreateCourseEvent($eventKey, $course);

            $this->setCourseEvent($event, $date, $duration, $capacity, $enteredTimeZone, $streamValues, $publish);

            $eventCollection->append($event);
        }

        return new EventErrorCollection($eventCollection, $failedDates);
    }


    private function getOrCreateCourseEvent(string $eventKey, $course): ?SingleEvent
    {
        if ( !$event = $this->singleEventRepository->getOneSingleEventByKey($eventKey)) {
            $event = new SingleEvent();
            $event->setParent($course);
            $event->setKey($eventKey);
            $event->setParentCourse($course);
        }

        return $event;
    }


    /**
     * @throws Exception
     */
    private function setCourseEvent(?SingleEvent $event, $date, ?string $duration, ?int $capacity, ?string $timeZone, array $streamValues = [], $publish = false): void {
        /** @var Carbon $date */
        $event->setCourseStartTime($date->format('H:i'));
        $event->setStartTimestamp($date->timestamp);
        $event->setCourseDate($date->startOfDay());
        $event->setDuration($duration);
        $event->setCapacity($capacity);
        $event->setMaxCapacity($capacity);
        $event->setWeekday($date->format('l'));
        $event->setEnteredTimeZone($timeZone);

        if (!empty($streamValues) > 0) {
            $event->setStreamingHost($streamValues['streamingHost']);
            $event->setStreamPassword($streamValues['streamPassword']);
            $event->setStreamLink($streamValues['streamLink']);
            $event->setAdditionalInformation($streamValues['additionalInformation']);
            $event->setMeetingId($streamValues['meetingId']);
        }

        $event->setDataModificationDate(new Carbon());

        if ($publish) {
            $event->setPublished(true);
        }

        $event->save();
    }

}
