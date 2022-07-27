<?php

namespace Tests\DataTransformer;

use App\DataTransformer\CoursesVppOutputDataTransformer;
use App\DataTransformer\Populator\CoursesVpp\CoursesVppOutputPopulatorInterface;
use App\Dto\VPP\Courses\CoursesVppOutputDto;
use App\Entity\Courses;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Course;
use stdClass;

class CoursesVppOutputDataTransformerTest extends TestCase
{
    private MockObject|CoursesVppOutputPopulatorInterface $populator;
    private CoursesVppOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(CoursesVppOutputPopulatorInterface::class);
        $this->dataTransformer = new CoursesVppOutputDataTransformer([$this->populator]);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new Course(), CoursesVppOutputDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new Course(), stdClass::class);
        self::assertFalse($isSupports);
    }

    public function testTransform()
    {
        $data = new CoursesVppOutputDto();
        $courseMock = $this->createMock(Courses::class);

        $this->populator->method('populate')->with($courseMock)->willReturn($data);

        $result = $this->dataTransformer->transform($courseMock, CoursesVppOutputDto::class);

        self::assertEquals($data, $result);
    }
}
