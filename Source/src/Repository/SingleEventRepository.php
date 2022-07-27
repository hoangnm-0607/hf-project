<?php


namespace App\Repository;


use App\Command\Interface\NotificationInterface;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Doctrine\DBAL\Query\QueryBuilder;
use Exception;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\SingleEvent;

final class SingleEventRepository
{
    /**
     * @throws Exception
     */
    public function getOneSingleEventById(int $id, bool $force = false): ?SingleEvent
    {
        return SingleEvent::getById($id, $force);
    }

    /**
     * @throws Exception
     */
    public function getPublishedSingleEventByEventId(string $eventId): ?SingleEvent
    {
        $singleEvent = SingleEvent::getById($eventId);

        return (($singleEvent) && $singleEvent->isPublished()) ? $singleEvent : null;
    }

    public function getOneSingleEventByKey(string $key): ?SingleEvent
    {
        $object = new SingleEvent\Listing();
        $object->setUnpublished(true)->setCondition('o_key LIKE ?', [$key])->setLimit(1);
        return $object->current() ?: null;
    }

    public function getAllSingleEventsByCourse(Course $course): ?array
    {
        Course::setHideUnpublished(false);
        $events = $course->getSingleEvents();
        Course::setHideUnpublished(true);

        return $events;
    }

    public function getOneSingleEventByDateTime(CarbonInterface $date, int $courseId): ?SingleEvent
    {
        $object = new SingleEvent\Listing();
        $object->setUnpublished(true)->setCondition(
            'CourseStartTime = ? AND CourseDate = ? AND o_parentId = ?',
            [
                $date->format('H:i'),
                $date->clone()->setTime(0,0)->getTimestamp(),
                $courseId
            ]
        )->setLimit(1);
        return $object->current() ?: null;
    }

    public function getFirstSingleEventByCourseId(int $courseId): ?SingleEvent
    {
        $object = new SingleEvent\Listing();
        $object->setUnpublished(true)->setCondition(
            'parentCourse__id = ? AND (Cancelled = 0 OR Cancelled IS NULL)',
            [$courseId]
        )->setOrderKey('CourseDate')->setOrder('ASC')->setLimit(1);
        return $object->current() ?: null;
    }

    public function getLastSingleEventByCourseId(int $courseId): ?SingleEvent
    {
        $object = new SingleEvent\Listing();
        $object->setUnpublished(true)->setCondition(
            'parentCourse__id = ? AND (Cancelled = 0 OR Cancelled IS NULL)',
            [$courseId]
        )->setOrderKey('CourseDate')->setOrder('DESC')->setLimit(1);
        return $object->current() ?: null;
    }

    public function getNextSingleEventByCourseId(int $courseId): ?SingleEvent
    {
        $object = new SingleEvent\Listing();
        $object->setCondition(
            'parentCourse__id = ? AND (Cancelled = 0 OR Cancelled IS NULL) AND startTimestamp > UNIX_TIMESTAMP()',
            [$courseId]
        )->setOrderKey('CourseDate')->setOrder('ASC')->setLimit(1);
        return $object->current() ?: null;
    }

    public function getOpenSingleEventsByCourseId(int $courseId): SingleEvent\Listing
    {
        $object = new SingleEvent\Listing();
        $object->setCondition(
            'parentCourse__id = ? AND (Cancelled = 0 OR Cancelled IS NULL) AND startTimestamp > UNIX_TIMESTAMP()',
            [$courseId]
        );
        return $object;
    }

    public function getPerformedSingleEventsByCourseId(int $courseId): SingleEvent\Listing
    {
        $object = new SingleEvent\Listing();
        $object->setUnpublished(true)->setCondition(
            'parentCourse__id = ? AND (Cancelled = 0 OR Cancelled IS NULL) AND startTimestamp < UNIX_TIMESTAMP()',
            [$courseId]
        );
        return $object;
    }

    public function getTotalSingleEventsByCourseId(int $courseId): SingleEvent\Listing
    {
        $object = new SingleEvent\Listing();
        $object->setUnpublished(true)->setCondition(
            'parentCourse__id = ?',
            [$courseId]
        );
        return $object;
    }

    public function getExpiredSingleEvents(): ?SingleEvent\Listing
    {
        $list = new SingleEvent\Listing();
        $list->setUnpublished(true);
        $list->setCondition('CourseDate <= ?', Carbon::now()->subHours(24)->timestamp);
        $list->addConditionParam("o_path NOT LIKE ?", '%Archive%');

        return $list;
    }

    public function getUnnotifiedCancelledEventsOfTheLast15Minutes(): array
    {
        $list = new SingleEvent\Listing();
        $list->setCondition('Cancelled = ? AND o_modificationdate >= ?', [true, Carbon::now()->subMinutes(15)->timestamp]);

        $listing = (array) $list->getData();

        return array_filter($listing, function ($singleEvent) {
            return $singleEvent->getBookings() && ( !$singleEvent->hasProperty(NotificationInterface::NOTIFIED_CANCELLED) || $singleEvent->getProperty(NotificationInterface::NOTIFIED_CANCELLED) === false );
        });
    }

    public function getUnnotifiedEventsStartingIn15Minutes(): array
    {
        $list = new SingleEvent\Listing();
        $list->setUnpublished(false);
        $list->setCondition('startTimestamp <= ? AND startTimestamp >= ?',
                            [
                                Carbon::now()->addMinutes(15)->timestamp,
                                Carbon::now()->subMinute()->timestamp,
                            ]);

        $listing = (array) $list->getData();

        return array_filter($listing, function ($singleEvent) {
            return  !$singleEvent->hasProperty(NotificationInterface::NOTIFIED_START) || $singleEvent->getProperty(NotificationInterface::NOTIFIED_START) === false;
        });
    }

    public function getUnnotifiedImminentEventsWithoutStreamLink(): array
    {
        $list = new SingleEvent\Listing();
        $list->setCondition('`startTimestamp` > ? and `startTimestamp` <= ? and `StreamLink` IS NULL', [Carbon::now()->timestamp, Carbon::now()->addHours(24)->timestamp]);

        $listing = (array) $list->getData();

        return array_filter($listing, function ($singleEvent) {
            return ( !$singleEvent->hasProperty(NotificationInterface::NOTIFIED_STREAMLINK) || $singleEvent->getProperty(NotificationInterface::NOTIFIED_STREAMLINK) !== '2' );
        });
    }

    public function getTopEventsByCheckinsForPartnerProfile(PartnerProfile $partnerProfile, int $limit): SingleEvent\Listing {
        $eventListing = new SingleEvent\Listing;

        $eventListing->onCreateQueryBuilder(
            function (QueryBuilder $queryBuilder) {
                $queryBuilder->join('object_SingleEvent', 'object_relations_booking', 'orb',
                    'object_SingleEvent.oo_id = orb.dest_id');
                $queryBuilder->join('orb', 'object_query_booking', 'booking',
                    'orb.src_id = booking.oo_id AND booking.CheckIn IS NOT NULL');
                $queryBuilder->groupBy('object_SingleEvent.o_id');
            }
        );
        $eventListing->setUnpublished(true);
        $eventListing->setCondition('(Cancelled = 0 OR Cancelled IS NULL) AND (MaxCapacity - Capacity) > 0 AND o_path like ?', [$partnerProfile->getFullPath() . '%']);

        $eventListing->setOrderKey(['count(*)'], false)->setOrder('DESC');
        $eventListing->setLimit($limit);

        return $eventListing;
    }

    public function getLastModifiedAndPublishedEvent(): ?SingleEvent
    {
        $listing = new SingleEvent\Listing();
        $listing->setCondition('o_path NOT LIKE ?', '%Archive%');
        $listing->setUnpublished(false)
            ->setOrderKey('o_modificationDate')
            ->setOrder('DESC')
            ->setLimit(1);
        return $listing->current() ?: null;
    }
}
