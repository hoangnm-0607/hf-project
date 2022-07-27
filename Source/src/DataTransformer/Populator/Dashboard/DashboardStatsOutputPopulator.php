<?php


namespace App\DataTransformer\Populator\Dashboard;


use App\Dto\VPP\Dashboard\DashboardEventDto;
use App\Dto\VPP\Dashboard\DashboardStatsDto;
use App\Entity\PartnerProfile;
use App\Repository\BookingRepository;
use App\Repository\SingleEventRepository;
use App\Service\I18NService;
use Carbon\Carbon;
use Doctrine\DBAL\Exception;
use Pimcore\Db;
use Pimcore\Model\DataObject\Course;

final class DashboardStatsOutputPopulator implements DashboardStatsOutputPopulatorInterface
{
    private Db\Connection|Db\ConnectionInterface $db;
    private I18NService $i18NService;
    private BookingRepository $bookingRepository;
    private SingleEventRepository $singleEventRepository;

    public function __construct(I18NService $i18NService, BookingRepository $bookingRepository, SingleEventRepository $singleEventRepository)
    {
        $this->i18NService = $i18NService;

        $this->db = Db::get();
        $this->bookingRepository = $bookingRepository;
        $this->singleEventRepository = $singleEventRepository;
    }


    /**
     * @throws Exception
     */
    public function populate(PartnerProfile $source, DashboardStatsDto $target): DashboardStatsDto
    {
        $target->performedEvents = $this->getPerformedEvents($source);
        $target->eventsPerWeek = $this->getEventsPerWeek($source);
        $target->top3Events = $this->getTop3Events($source);

        return $target;
    }


    /**
     * @throws Exception
     */
    public function getPerformedEvents(PartnerProfile $partner): int {

        return $this->db->fetchOne('SELECT count(*) FROM object_singleevent WHERE startTimestamp < UNIX_TIMESTAMP() AND Cancelled = 0 AND o_path like ?', [$partner->getFullPath() . '%']);

    }

    /**
     * @throws Exception
     */
    public function getEventsPerWeek(PartnerProfile $partner): array {

        $rows =  $this->db->fetchAllKeyValue(
            'SELECT WEEKOFYEAR(FROM_UNIXTIME(startTimestamp)) as calender_week, count(*) FROM object_singleevent
            WHERE Cancelled = 0 AND o_path like ?
            AND startTimestamp between UNIX_TIMESTAMP(DATE_SUB(CURRENT_DATE, INTERVAL (WEEKDAY(CURRENT_DATE)+21) DAY))
                AND UNIX_TIMESTAMP(DATE_SUB(CURRENT_DATE, INTERVAL (WEEKDAY(CURRENT_DATE)) DAY))
            GROUP BY calender_week  order by startTimestamp', [$partner->getFullPath() . '%']
        );

        $result =[];
        if($rows) {
            foreach ($rows as $key => $value) {
                $result[] = [
                    'week' => intval($key),
                    'amount'=> intval($value)
                ];
            }
        }
        return $result;
    }

    public function getTop3Events(PartnerProfile $partner): array {

        $eventListing = $this->singleEventRepository->getTopEventsByCheckinsForPartnerProfile($partner, 3);

        $language = $this->i18NService->getLanguageFromRequest();

        $top3 = [];
        Course::setHideUnpublished(false);
        foreach ($eventListing as $event) {
            $eventDto = new DashboardEventDto();
            $eventDto->courseName = $event->getParentCourse()->getCourseName($language);
            $eventDto->bookings = $this->bookingRepository->getNumberBookingsForEvent($event);
            $eventDto->checkins = $this->bookingRepository->getNumberCheckinsForEvent($event);
            $eventDto->eventDate = $event->getCourseDate()->format('Y-m-d');
            $eventDto->eventTime = $event->getCourseStartTime();

            if ($event->getEnteredTimeZone() != date_default_timezone_get()) {
                $dateTime = new Carbon($event->getCourseDate()->toDateString() . ' ' . $event->getCourseStartTime() . ':00', date_default_timezone_get());
                $dateTime->setTimezone($event->getEnteredTimeZone());
                $eventDto->eventDate = $dateTime->format('Y-m-d');
                $eventDto->eventTime = $dateTime->format('H:i');
            }
            else {
                $eventDto->eventDate = $event->getCourseDate()->toDateString();
                $eventDto->eventTime = $event->getCourseStartTime();
            }

            $top3[] = $eventDto;
        }
        Course::setHideUnpublished(true);

        return $top3;
    }

}
