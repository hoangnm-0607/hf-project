<?php


namespace App\DataTransformer\Populator\CourseCategory;


use App\Dto\CourseCategoryDto;
use Pimcore\Model\DataObject\CourseCategory;

interface CourseCategoryOutputPopulatorInterface
{
    public function populate(CourseCategory $source, CourseCategoryDto $target): CourseCategoryDto;
}
