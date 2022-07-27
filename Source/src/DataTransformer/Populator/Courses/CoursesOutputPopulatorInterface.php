<?php


namespace App\DataTransformer\Populator\Courses;


use App\Dto\CoursesDto;
use App\Entity\Courses;

interface CoursesOutputPopulatorInterface
{
    public function populate(Courses $source, CoursesDto $target, ?string $userId): CoursesDto;
}
