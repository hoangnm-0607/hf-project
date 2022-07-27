<?php

namespace Tests\DataTransformer;

use App\DataTransformer\DatedCoursesOutputDataTransformer;
use App\DataTransformer\Populator\Courses\CoursesOutputPopulatorInterface;
use App\Dto\CoursesDto;
use App\Dto\DatedCoursesDto;
use App\Entity\Courses;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class CoursesOutputDataTransformerTest extends TestCase
{

    private DatedCoursesOutputDataTransformer $coursesOutputDataTransformer;
    private CoursesOutputPopulatorInterface|MockObject $populatorMock;

    protected function setUp(): void
    {
        $this->populatorMock                = $this->createMock(CoursesOutputPopulatorInterface::class);
        $this->coursesOutputDataTransformer = new DatedCoursesOutputDataTransformer([$this->populatorMock]);
    }

    public function testSupportsTransformationCourses()
    {
        $supports = $this->coursesOutputDataTransformer->supportsTransformation(new Courses(), DatedCoursesDto::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->coursesOutputDataTransformer->supportsTransformation(new stdClass(), DatedCoursesDto::class);

        self::assertFalse($supports);
    }

    public function testTransform()
    {
        $data = new CoursesDto();
        $coursesMock = $this->createMock(Courses::class);
        $context['request_uri'] = '/xyz?userId=12345';

        $this->populatorMock->method('populate')->with($coursesMock)->willReturn($data);

        $result = $this->coursesOutputDataTransformer->transform($coursesMock, CoursesDto::class, $context);

        self::assertEquals($data, $result);
    }
}
