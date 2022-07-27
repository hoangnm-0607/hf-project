<?php

namespace Tests\DataProvider;

use App\DataProvider\CoursesCollectionDataProvider;
use App\DataProvider\Helper\CourseDataProviderHelper;
use App\Entity\Courses;
use App\Exception\TokenIdMismatchException;
use App\Security\Validator\InMemoryUserValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class CoursesCollectionDataProviderTest extends TestCase
{

    private MockObject|CourseDataProviderHelper $courseHelperMock;
    private CoursesCollectionDataProvider $coursesCollectionDataProvider;

    protected function setUp(): void
    {
        $this->courseHelperMock              = $this->createMock(CourseDataProviderHelper::class);
        $inMemoryUserValidatorMock           = $this->createMock(InMemoryUserValidator::class);
        $this->coursesCollectionDataProvider = new CoursesCollectionDataProvider(20, $this->courseHelperMock, $inMemoryUserValidatorMock);
    }

    public function testSupportsPartnerProfileEntity(): void
    {
        $result = $this->coursesCollectionDataProvider->supports(Courses::class);
        self::assertTrue($result);
    }

    public function testDoesNotSupportWrongEntity(): void
    {
        $result = $this->coursesCollectionDataProvider->supports(stdClass::class);
        self::assertFalse($result);
    }

    /**
     * @throws TokenIdMismatchException
     */
    public function testGetCollectionReturnsNonEmptyGenerator(): void
    {
        $context['filters']['lastUpdateTimestamp'] = 1524268847;
        $context['filters']['userId'] = null;
        $context['filters']['page'] = 1;
        $condition = [
            'o_modificationDate > (?)',
            $context['filters']['lastUpdateTimestamp'],
        ];
        $expectedIds = [255,263];
        $this->courseHelperMock->method('getCourseIdsOfModifiedCoursesOrEvents')->with($condition)->willReturn($expectedIds);

        $result = $this->coursesCollectionDataProvider->getCollection(Courses::class, null, $context);

        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self::assertEmpty($resultCollection);
    }

    /**
     * @throws TokenIdMismatchException
     */
    public function testGetCollectionWithNonExistentUserReturnsEmptyArray():void
    {
        $context['filters']['lastUpdateTimestamp'] = 1524268847;
        $context['filters']['userId'] = '123131231';
        $context['filters']['page'] = 1;
        $result = $this->coursesCollectionDataProvider->getCollection(Courses::class, null, $context);

        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self::assertEmpty($resultCollection);
    }

    /**
     * @throws TokenIdMismatchException
     */
    public function testGetCollectionWithFutureTimestampReturnsEmptyData():void
    {
        $context['filters']['lastUpdateTimestamp'] = 1924268847;
        $context['filters']['userId'] = null;

        $result = $this->coursesCollectionDataProvider->getCollection(Courses::class, 'GET', $context);
        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self::assertEmpty($resultCollection);
    }

}
