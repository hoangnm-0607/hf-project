<?php

namespace Tests\DataTransformer;

use App\DataTransformer\CourseCategoryOutputDataTransformer;
use App\DataTransformer\Populator\CourseCategory\CourseCategoryOutputPopulatorInterface;
use App\Dto\CourseCategoryDto;
use App\Entity\CourseCategory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class CourseCategoryOutputDataTransformerTest extends TestCase
{
    private CourseCategoryOutputDataTransformer $courseCategoryOutputDataTransformer;
    private MockObject|CourseCategoryOutputPopulatorInterface $populatorMock;

    protected function setUp(): void
    {
        $this->populatorMock                       = $this->createMock(CourseCategoryOutputPopulatorInterface::class);
        $this->courseCategoryOutputDataTransformer = new CourseCategoryOutputDataTransformer([$this->populatorMock]);
    }

    public function testSupportsTransformationCourseCategory()
    {
        $supports = $this->courseCategoryOutputDataTransformer->supportsTransformation(new CourseCategory(), CourseCategoryDto::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->courseCategoryOutputDataTransformer->supportsTransformation(new stdClass(), CourseCategoryDto::class);

        self::assertFalse($supports);
    }

    public function testTransform()
    {
        $data = new CourseCategoryDto();
        $courseCategoryMock = $this->createMock(CourseCategory::class);

        $this->populatorMock->method('populate')->with($courseCategoryMock)->willReturn($data);

        $result = $this->courseCategoryOutputDataTransformer->transform($courseCategoryMock, CourseCategoryDto::class);

        self::assertEquals($data, $result);
    }
}
