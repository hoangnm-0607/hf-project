<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\CourseCategoryCollectionDataProvider;
use App\Entity\CourseCategory;
use PHPUnit\Framework\TestCase;
use stdClass;

class CourseCategoryCollectionDataProviderTest extends TestCase
{
    private CourseCategoryCollectionDataProvider $courseCategoryCollectionDataProvider;

    protected function setUp(): void
    {
        $this->courseCategoryCollectionDataProvider = new CourseCategoryCollectionDataProvider(20);
    }

    public function testSupportsPartnerProfileEntity(): void
    {
        $result = $this->courseCategoryCollectionDataProvider->supports(CourseCategory::class);
        self::assertTrue($result);
    }

    public function testDoesNotSupportWrongEntity(): void
    {
        $result = $this->courseCategoryCollectionDataProvider->supports(stdClass::class);
        self::assertFalse($result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testGetCollectionReturnsNonEmptyGenerator(): void
    {
        $context['filters']['lastUpdateTimestamp'] = 1524268847;
        $context['filters']['page'] = 1;

        $result = $this->courseCategoryCollectionDataProvider->getCollection(CourseCategory::class, null, $context);

        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self:self::assertNotEmpty($resultCollection);
    }
}
