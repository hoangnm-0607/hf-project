<?php


namespace App\DataTransformer\Populator\CoursesVpp;


use App\Dto\VPP\Courses\CoursesVppOutputDto;
use App\Entity\Courses;

interface CoursesVppOutputPopulatorInterface
{
    public function populate(Courses $source, CoursesVppOutputDto $target): CoursesVppOutputDto;
}
