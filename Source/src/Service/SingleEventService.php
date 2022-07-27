<?php


namespace App\Service;


use App\Repository\SingleEventRepository;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Pimcore\Model\DataObject\Booking;
use Pimcore\Model\DataObject\SingleEvent;

class SingleEventService
{
    private SingleEventRepository $singleEventRepository;

    public function __construct(SingleEventRepository $singleEventRepository)
    {
        $this->singleEventRepository = $singleEventRepository;
    }

    /**
     * Returns false, if event
     * - date is in the future AND
     * - has uncancelled bookings AND
     * - isn't cancelled
     *
     * @param SingleEvent[] $singleEvents
     */
    public function checkIfEventsMayUnpublish(array $singleEvents): bool
    {
        foreach ($singleEvents as $singleEvent) {
            if ($this->getEventDateTimeTimestamp($singleEvent) > Carbon::now()->timestamp
                && $this->hasUncancelledBookings($singleEvent)
                && !$singleEvent->getCancelled()) {
                return false;
            }
        }
        return true;
    }

    public function checkIfEventsMayCancel(SingleEvent $singleEvent): bool
    {
        if ($this->getEventDateTimeTimestamp($singleEvent) <= Carbon::now()->addMinutes(5)->timestamp) {
            return false;
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function checkIfThisIsAnUnpublishEvent(SingleEvent $event): bool
    {
        if(($originalEventObject = $this->singleEventRepository->getOneSingleEventById($event->getId(),true))
           && $originalEventObject->getPublished() && ! $event->getPublished()) {
                return true;
            }

        return false;
    }
    /**
     * @throws Exception
     */
    public function checkIfThisIsACancelEvent(SingleEvent $event): bool
    {
        if(($originalEventObject = $this->singleEventRepository->getOneSingleEventById($event->getId(),true))
           && ! $originalEventObject->getCancelled() && $event->getCancelled()) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function checkIfThisIsAnArchivedEvent(SingleEvent $event): bool
    {
       return strpos($event->getFullPath(), '/Archive/') > 0;
    }

    /**
     * @throws Exception
     */
    public function checkIfEventExistByDateTime(CarbonInterface $date, int $courseId): bool
    {
        return (bool)$this->singleEventRepository->getOneSingleEventByDateTime($date, $courseId);
    }

    public function getEventDateTimeTimestamp(SingleEvent $event): int
    {
        $eventDateTime  = $event->getCourseDate();
        $eventStartTime = explode(':', $event->getCourseStartTime());

        // remember to use copy, otherwise the original Carbon object will be modified
        $eventDateTime = $eventDateTime->copy()
                                       ->setTimezone('Europe/Berlin')
                                       ->setHours($eventStartTime[0])
                                       ->setMinutes($eventStartTime[1])
                                       ->setTimezone('UTC');

        return $eventDateTime->timestamp;
    }

    public function generateEventKey(int $courseId, CarbonInterface $date): string {
        return $courseId . '__' . $date->format('y-m-d-H');
    }

    private function hasUncancelledBookings(SingleEvent $singleEvent): bool
    {
        if($bookings = $singleEvent->getBookings()) {
            /** @var Booking $booking */
            foreach($bookings as $booking) {
                if(!$booking->getUserCancelled()) {
                    return true;
                }
            }
        }
        return false;
    }

}
