<?php


namespace App\DataTransformer\Populator\Courses;


use App\Dto\CoursesDto;
use App\Entity\Courses;

class CoursesCategoryOutputPopulator implements CoursesOutputPopulatorInterface
{

    public function populate(Courses $source, CoursesDto $target, $userId): CoursesDto
    {
        if ($mainCategory = $source->getMainCategory()) {
            $target->mainCategory = $mainCategory[0]->getId();
        }

        if($secondaryCategories = $source->getSecondaryCategories()) {
            foreach ($secondaryCategories as $category) {
                $target->secondaryCategories[] = $category->getId();
            }
        }

        return $target;
    }
}
