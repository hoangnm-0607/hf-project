<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\PartnerCategoryCollectionDataProvider;
use App\Entity\PartnerCategory;
use PHPUnit\Framework\TestCase;
use stdClass;

class PartnerCategoryCollectionDataProviderTest extends TestCase
{

    private PartnerCategoryCollectionDataProvider $partnerCategoryCollectionDataProvider;

    protected function setUp(): void
    {
        $this->partnerCategoryCollectionDataProvider = new PartnerCategoryCollectionDataProvider(20);
    }

    public function testSupportsPartnerProfileEntity(): void
    {
        $result = $this->partnerCategoryCollectionDataProvider->supports(PartnerCategory::class);
        self::assertTrue($result);
    }

    public function testDoesNotSupportWrongEntity(): void
    {
        $result = $this->partnerCategoryCollectionDataProvider->supports(stdClass::class);
        self::assertFalse($result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testGetCollectionReturnsNonEmptyGenerator(): void
    {
        $context['filters']['lastUpdateTimestamp'] = 1524268847;
        $context['filters']['page'] = 1;
        $result = $this->partnerCategoryCollectionDataProvider->getCollection(PartnerCategory::class, null, $context);

        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self:self::assertNotEmpty($resultCollection);
    }
}
