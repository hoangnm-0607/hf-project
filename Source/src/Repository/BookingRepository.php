<?php


namespace App\Repository;


use Doctrine\ORM\EntityManagerInterface;
use Pimcore\Model\DataObject\Booking;
use Pimcore\Model\DataObject\SingleEvent;
use Pimcore\Model\DataObject\CourseUser;

class BookingRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entityManager = $entityManager;
    }

    public function getOneBookingByBookingId(string $bookingId): ?Booking
    {
        return Booking::getByBookingId($bookingId, ['limit' => 1, 'unpublished' => false]);
    }

    public function getBookingListingByUser(CourseUser $courseUser): Booking\Listing|array|null
    {
        return (new Booking\Listing())->setUnpublished(false)->filterByUser($courseUser) ?? [];
    }

    public function getNumberBookingsForEvent(SingleEvent $event): int
    {
        $bookings = new Booking\Listing;
        $bookings->setUnpublished(true);
        $bookings->filterByEvent($event);
        $bookings->setCondition('UserCancelled = 0');

        return $bookings->getTotalCount();
    }

    public function getNumberCheckinsForEvent(SingleEvent $event): int
    {
        $bookings = new Booking\Listing;
        $bookings->setUnpublished(true);
        $bookings->filterByEvent($event);
        $bookings->setCondition('UserCancelled = 0 AND CheckIn IS NOT NULL');

        return $bookings->getTotalCount();
    }

    public function getBookingsForEvent(SingleEvent $event, bool $onlyCheckedIn) {
        $list = new Booking\Listing();
        $conditions = [
            'Event__id = ?',
            'UserCancelled = 0'
        ];
        if ($onlyCheckedIn) {
            $conditions[] = 'CheckIn IS NOT NULL';
        }
        $list->setCondition(implode(' AND ', $conditions), [$event->getId()]);

        return $list;
    }
}
