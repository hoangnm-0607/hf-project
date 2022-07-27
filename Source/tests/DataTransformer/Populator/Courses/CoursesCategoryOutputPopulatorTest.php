<?php

namespace Tests\DataTransformer\Populator\Courses;

use App\DataTransformer\Populator\Courses\CoursesCategoryOutputPopulator;
use App\Dto\CoursesDto;
use App\Entity\CourseCategory;
use App\Entity\Courses;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;

class CoursesCategoryOutputPopulatorTest extends TestCase
{

    public function testPopulate(): void
    {
        $populator = new CoursesCategoryOutputPopulator();
        $target    = new CoursesDto();

        $output = $populator->populate($this->createInput(), $target, '');

        self::assertEquals($this->createExpectedOutput(), $output);
    }

    private function createInput(): Courses
    {
        $mainCategory = new CourseCategory();
        $mainCategory->setId(555);

        $cat1 = new CourseCategory();
        $cat1->setId(255);
        $cat2 = new CourseCategory();
        $cat2->setId(355);

        $input = new Courses();
        $input->setMainCategory([$mainCategory]);
        $input->setSecondaryCategories([$cat1, $cat2]);

        return $input;
    }

    #[Pure] private function createExpectedOutput(): CoursesDto
    {
        $output = new CoursesDto();
        $output->mainCategory = 555;
        $output->secondaryCategories = [255,355];

        return $output;
    }


}
