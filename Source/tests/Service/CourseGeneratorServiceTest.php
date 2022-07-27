<?php

namespace Tests\Service;

use App\Repository\SingleEventRepository;
use App\Service\CourseGeneratorService;
use App\Service\SingleEventService;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Course;

class CourseGeneratorServiceTest extends TestCase
{
    protected SingleEventRepository|MockObject $singleEventRepositoryMock;
    protected CourseGeneratorService $generatorService;
    protected SingleEventService $singleEventService;
    protected MockObject|Course $courseMock;

    public function setUp(): void
    {
        $this->singleEventRepositoryMock = $this->createMock(SingleEventRepository::class);
        $this->courseMock = $this->createMock(Course::class);

        $this->singleEventService = new SingleEventService($this->singleEventRepositoryMock);
        $this->generatorService = new CourseGeneratorService($this->singleEventRepositoryMock, $this->singleEventService);
    }

    /**
     * @test
     */
    public function GetEventSeriesDates_ReturnsSingleDateWhenRepetitionIsZero()
    {
        $this->courseMock->method('getCourseDate')->willReturn(new Carbon('2021-06-22'));

        $repetition = 0;
        $output     = $this->generatorService->getEventSeriesDates(
            $repetition,
            $this->courseMock->getWeekdays(),
            $this->courseMock->getCourseDate(),
            $this->courseMock->getEndDate()
        );

        self::assertEquals([new Carbon('2021-06-22')], $output);

    }

    /**
     * @test
     */
    public function GetEventSeriesDates_ReturnsCertainDate()
    {
        $this->courseMock->method('getWeekdays')->willReturn([1]);
        $this->courseMock->method('getCourseDate')->willReturn(new Carbon('2021-06-22'));
        $this->courseMock->method('getEndDate')->willReturn(new Carbon('2021-06-30'));

        $repetition = 1;
        $output     = $this->generatorService->getEventSeriesDates(
            $repetition,
            $this->courseMock->getWeekdays(),
            $this->courseMock->getCourseDate(),
            $this->courseMock->getEndDate()
        );

        self::assertEquals(new Carbon('2021-06-28'), $output->current());
    }
    /**
     * @test
     */
    public function GetEventSeriesDates_FailsToReturnCertainDate()
    {
        $this->courseMock->method('getWeekdays')->willReturn([1]);
        $this->courseMock->method('getCourseDate')->willReturn(new Carbon('2021-06-22'));
        $this->courseMock->method('getEndDate')->willReturn(new Carbon('2021-06-30'));

        $repetition = 1;
        $output     = $this->generatorService->getEventSeriesDates(
            $repetition,
            $this->courseMock->getWeekdays(),
            $this->courseMock->getCourseDate(),
            $this->courseMock->getEndDate()
        );

        self::assertNotEquals(new Carbon('2021-06-29'), $output->current());
    }

    /**
     * @test
     */
    public function GenerateCourseDatesByWeekdays_ReturnsCertainNumberOfDates()
    {
        $periodicity = [
            'days'        => [1,3], // monday + wednesday
            'repeatWeeks' => 2, // every second week
        ];
        $entry_start = new Carbon('2020-02-02');
        $periodicity_end = new Carbon('2020-03-03');
        $output = $this->generatorService->generateCourseDatesByWeekdays($periodicity, $entry_start, $periodicity_end);

        $expected_output = 5; // number of generated dates

        $count = $output->count();
        self::assertEquals($expected_output, $count);
    }

    /**
     * @test
     */
    public function GenerateCourseDatesByWeekdays_FailsToReturnCertainNumberOfDates()
    {
        $periodicity = [
            'days'        => [1,3], // monday + wednesday
            'repeatWeeks' => 2, // every second week
        ];
        $entry_start = new Carbon('2020-02-02');
        $periodicity_end = new Carbon('2020-03-03');
        $output = $this->generatorService->generateCourseDatesByWeekdays($periodicity, $entry_start, $periodicity_end);

        $expected_output = 10; // wrong number of generated dates

        $count = $output->count();
        self::assertNotEquals($expected_output, $count);
    }
}
