<?php


namespace App\Repository;


use Exception;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\Course\Listing;
use Pimcore\Model\DataObject\PartnerProfile;

class CourseRepository
{
    /**
     * @throws Exception
     */
    public function getOneCourseById(int $id, bool $force = true): ?Course
    {
        return Course::getById($id, $force);
    }

    public function getUnpublishedSingleEvents($course): array
    {
        /** @var Course $course */
        $course::setHideUnpublished(false);
        return $course->getSingleEvents();
    }

    public function getAllCoursesOfPartner(PartnerProfile $partnerProfile, bool $includeArchived = false): Listing
    {
        $path = false === $includeArchived  ? $partnerProfile->getCurrentFullPath() . '/Courses/%' : $partnerProfile->getCurrentFullPath() . '%';

        $courses = new Listing();
        $courses->setCondition('o_path LIKE ?', $path);

        return $courses;

    }

    public function getLastModifiedCourse(): ?Course
    {
        $listing = new Listing();
        $listing->setCondition('o_path NOT LIKE ?', '%Archive%');
        $listing->setUnpublished(true)
            ->setOrderKey('o_modificationDate')
            ->setOrder('DESC')
            ->setLimit(1);
        return $listing->current() ?: null;
    }
}
