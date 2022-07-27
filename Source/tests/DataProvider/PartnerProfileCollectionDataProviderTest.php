<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\PartnerProfileCollectionDataProvider;
use App\Entity\PartnerProfile;
use PHPUnit\Framework\TestCase;
use stdClass;

class PartnerProfileCollectionDataProviderTest extends TestCase
{

    private PartnerProfileCollectionDataProvider $partnerProfileCollectionDataProvider;

    protected function setUp(): void
    {
        $this->partnerProfileCollectionDataProvider = new PartnerProfileCollectionDataProvider(20);
    }

    public function testSupportsPartnerProfileEntity(): void
    {
        $result = $this->partnerProfileCollectionDataProvider->supports(PartnerProfile::class, 'get');
        self::assertTrue($result);
    }

    public function testDoesNotSupportWrongEntity(): void
    {
        $result = $this->partnerProfileCollectionDataProvider->supports(stdClass::class);
        self::assertFalse($result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testGetCollectionReturnsNonEmptyGenerator(): void
    {
        $context['filters']['lastUpdateTimestamp'] = 1524268847;
        $context['filters']['page'] = 1;
        $result = $this->partnerProfileCollectionDataProvider->getCollection(PartnerProfile::class, null, $context);

        $resultCollection = [];
        foreach ($result as $yielded) {
            $resultCollection[] = $yielded;
        }

        self:self::assertNotEmpty($resultCollection);
    }
}
