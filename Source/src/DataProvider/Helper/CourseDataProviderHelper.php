<?php


namespace App\DataProvider\Helper;


use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Repository\CourseUserRepository;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\SingleEvent;

class CourseDataProviderHelper
{
    private CourseUserRepository $courseUserRepository;
    private BookingRepository $bookingRepository;

    public function __construct(CourseUserRepository $courseUserRepository, BookingRepository $bookingRepository)
    {
        $this->courseUserRepository = $courseUserRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function getCourseIdsOfBookedEvents(string $userId): array
    {
        $courseIds = [];

        if (($courseUser = $this->courseUserRepository->getCourseUserByUserId($userId))
            && $bookings = $this->bookingRepository->getBookingListingByUser($courseUser)) {
            /** @var Booking $booking */
            foreach ($bookings as $booking) {
                if (($singleEvent = $booking->getEvent())
                    && ($parentCourse = $singleEvent->getParentCourse())) {
                    $courseIds[] = $parentCourse->getId();
                }
            }
        }
        return array_unique($courseIds);
    }

    public function getCourseIdsOfModifiedCoursesOrEvents(array $condition): array
    {
        $courseIds = $this->getCourseIdsByModificationDate($condition);

        $courseIdsFromEvents = $this->getCourseIdsFromEventsByDataModificationDate($condition);

        return array_unique(array_merge($courseIdsFromEvents, $courseIds));
    }

    private function getCourseIdsByModificationDate(array $condition): array
    {
        $courseListing = new Course\Listing();
        $courseListing->setUnpublished(false)
                      ->setOrderKey('o_modificationDate')
                      ->setCondition($condition[0], [$condition[1]]);

        $courseIds = [];
        foreach ($courseListing as $course) {
            $courseIds[] = $course->getId();
        }

        return $courseIds;
    }

    private function getCourseIdsFromEventsByDataModificationDate(array $condition): array
    {
        // SingleEvents have their own date field for modifications, to ignore changes of the capacity fields
        // so we'll change the orderkey and condition to that field
        $eventListing = new SingleEvent\Listing();
        $eventListing->setOrderKey('DataModificationDate')
                     ->setCondition('DataModificationDate >= (?)', [$condition[1]]);

        $courseIdsFromEvents = [];
        if($eventListing->getCount() > 0) {
            foreach ($eventListing as $event) {
                if($parentCourse = $event->getParentCourse()) {
                    $courseIdsFromEvents[] = $parentCourse->getId();
                }
            }
        }

        return array_unique($courseIdsFromEvents);
    }

}
