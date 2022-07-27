<?php

namespace Tests\DataTransformer\Populator\Courses;

use App\DataTransformer\Populator\Courses\CoursesCasConfigOutputPopulator;
use App\Dto\CoursesDto;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Service\DataObjectService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CoursesCasConfigOutputPopulatorTest extends TestCase
{
    private DataObjectService|MockObject $dataObjectServiceMock;

    protected function setUp(): void
    {
        $this->dataObjectServiceMock = $this->createMock(DataObjectService::class);
        $parentProfileMock           = $this->createMock(PartnerProfile::class);

        $parentProfileMock
            ->method('getPartnerId')
            ->willReturn(9090);

        $parentProfileMock
            ->method('getConfigCheckInApp')
            ->willReturn('abc1234');

        $this->dataObjectServiceMock
            ->method('getRecursiveParent')
            ->willReturn($parentProfileMock);
    }

    public function testPopulateWillReturnConfigCheckInApp()
    {


        $populator = new CoursesCasConfigOutputPopulator($this->dataObjectServiceMock);
        $target    = new CoursesDto();

        $expectedOutput = new CoursesDto();
        $expectedOutput->casConfigCheckInApp = 'abc1234';

        $output = $populator->populate($this->createInput(), $target, '');
        self::assertEquals($expectedOutput, $output);
    }

    private function createInput(): Courses
    {
        $input = new Courses();
        $input->setExclusiveCourse(true);

        return $input;

    }
}
