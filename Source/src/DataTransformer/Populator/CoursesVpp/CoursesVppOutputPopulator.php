<?php


namespace App\DataTransformer\Populator\CoursesVpp;


use App\Dto\VPP\Courses\CoursesVppOutputDto;
use App\Entity\Courses;
use App\Repository\SingleEventRepository;

final class CoursesVppOutputPopulator implements CoursesVppOutputPopulatorInterface
{
    private SingleEventRepository $singleEventRepository;

    public function __construct(SingleEventRepository $singleEventRepository)
    {
        $this->singleEventRepository = $singleEventRepository;
    }


    public function populate(Courses $source, CoursesVppOutputDto $target): CoursesVppOutputDto
    {
        $target->courseId  = $source->getCourseID() ?? '';

        $target->internalName = $source->getKey();
        $target->courseName['de'] = $source->getCourseName('de');
        $target->courseName['en'] = $source->getCourseName('en');
        $target->courseType = $source->getCourseType() ?? '';
        $target->shortDescription['de'] = $source->getDescription('de');
        $target->shortDescription['en'] = $source->getDescription('en');
        $target->neededAccessoires['de'] = $source->getNeededAccessoires('de');
        $target->neededAccessoires['en'] = $source->getNeededAccessoires('en');
        $target->level = $this->getLevel($source);
        $target->courseDuration = $source->getDuration();
        $target->capacity = $source->getCapacity();
        $target->exclusiveCourse = $source->getExclusiveCourse() ?? false;
        $target->courseInstructor = $source->getCourseInstructor();
        $target->published = $source->isPublished();
        $target->archived = !str_ends_with($source->getPath(), '/Courses/');

        if ($mainCategory = $source->getMainCategory()) {
            $target->mainCategory = $mainCategory[0]->getId();
        }

        if ($secondaryCategories = $source->getSecondaryCategories()) {
            foreach ($secondaryCategories as $category) {
                $target->secondaryCategories[] = $category->getId();
            }
        }

        $this->setEventRelatedFields($source, $target);

        return $target;
    }

    public function getLevel(Courses $source): ?string {
        if ($source->getLevel() == null)  {
            return null;
        }
        return count($source->getLevel()) > 1 ? '' : $source->getLevel()[0];
    }

    public function setEventRelatedFields(Courses $course, CoursesVppOutputDto $target) {
        $courseId = $course->getId();
        $target->startDate = $this->singleEventRepository->getFirstSingleEventByCourseId($courseId)?->getCourseDate()->format('Y-m-d');
        $target->endDate = $this->singleEventRepository->getLastSingleEventByCourseId($courseId)?->getCourseDate()->format('Y-m-d');
        $target->nextEventDate = $this->singleEventRepository->getNextSingleEventByCourseId($courseId)?->getCourseDate()->format('Y-m-d');
        $target->openEvents = $this->singleEventRepository->getOpenSingleEventsByCourseId($courseId)?->count();
        $target->performedEvents = $this->singleEventRepository->getPerformedSingleEventsByCourseId($courseId)?->count();
        $target->totalEvents = $this->singleEventRepository->getTotalSingleEventsByCourseId($courseId)?->count();
    }

}
