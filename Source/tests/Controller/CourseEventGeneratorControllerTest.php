<?php

namespace Tests\Controller;

use App\Controller\CourseEventGeneratorController;
use App\DataCollection\EventErrorCollection;
use App\Repository\CourseRepository;
use App\Service\CourseGeneratorService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Course;
use Symfony\Component\HttpFoundation\Request;

class CourseEventGeneratorControllerTest extends TestCase
{
    private MockObject|Course $courseMock;
    private Request|MockObject $requestMock;
    private CourseEventGeneratorController $courseEventGeneratorController;
    private CourseRepository|MockObject $courseRepositoryMock;
    private MockObject|CourseGeneratorService $courseGeneratorServiceMock;

    protected function setUp(): void
    {
        $this->courseGeneratorServiceMock = $this->createMock(CourseGeneratorService::class);
        $this->courseRepositoryMock = $this->createMock(CourseRepository::class);
        $this->courseEventGeneratorController = new CourseEventGeneratorController($this->courseGeneratorServiceMock, $this->courseRepositoryMock);
        $this->requestMock = $this->createMock(Request::class);
        $this->courseMock = $this->createMock(Course::class);
    }

    /**
     * @test
     * @throws Exception
     */
    public function GenerateCourseEvents_ReturnsFailureIfNoObjectFound()
    {
        $this->requestMock->method('get')->with('object_id')->willReturn(null);
        $output = $this->courseEventGeneratorController->generateCourseEvents($this->requestMock);
        self::assertEquals(json_encode('failure'), $output->getContent());
    }

    /**
     * @test
     * @throws Exception
     */
    public function GenerateCourseEvents_ReturnsSuccessIfNoObjectFound()
    {
        $this->courseMock->method('save')->willReturn(new Course());
        $this->courseMock->method('getKey')->willReturn('fake');
        $this->courseMock->method('getRepetition')->willReturn('0');
        $this->courseGeneratorServiceMock->method('getEventSeriesDates')->willReturn([]);
        $this->courseGeneratorServiceMock->method('generateNewEventCollection')->willReturn(new EventErrorCollection(new \ArrayIterator()));
        $this->courseRepositoryMock->method('getUnpublishedSingleEvents')->willReturn([]);
        $this->requestMock->method('get')->with('object_id')->willReturn('2222');
        $this->courseRepositoryMock->method('getOneCourseById')->with('2222')->willReturn($this->courseMock);

        $output = $this->courseEventGeneratorController->generateCourseEvents($this->requestMock);
        self::assertEquals(json_encode('success'), $output->getContent());
    }
}
