<?php

namespace Tests\DataTransformer\Populator;

use App\DataTransformer\Populator\PartnerProfile\PartnerProfileCategoryOutputPopulator;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\PartnerCategory;

class PartnerProfileCategoryOutputPopulatorTest extends TestCase
{
    /**
     * @var PartnerProfile|mixed|MockObject
     */
    private mixed $partnerProfileMock;

    public function setUp():void
    {
        $this->partnerProfileMock = $this->createMock(PartnerProfile::class);
        $primaryCategoryMock      = $this->createMock(PartnerCategory::class);
        $category1Mock            = $this->createMock(PartnerCategory::class);
        $category2Mock = $this->createMock(PartnerCategory::class);

        $primaryCategoryMock->method('getId')->willReturn(100);
        $category1Mock->method('getId')->willReturn(200);
        $category2Mock->method('getId')->willReturn(201);

        $this->partnerProfileMock->method('getPartnerCategoryPrimary')->willReturn($primaryCategoryMock);
        $this->partnerProfileMock->method('getPartnerCategorySecondary')->willReturn([
                                                                                         $category1Mock,
                                                                                         $category2Mock
                                                                                     ]);
    }

    public function testPopulate(): void
    {

        $partnerPorfileCategoryPopulator = new PartnerProfileCategoryOutputPopulator();

        $target = new PartnerProfileDto();

        $output = $partnerPorfileCategoryPopulator->populate($this->partnerProfileMock, $target);

        self::assertEquals(
            $this->createExpectedOutput(),
            $output
        );


    }

    private function createExpectedOutput(): PartnerProfileDto
    {
        $expectedOutput = new PartnerProfileDto();
        $expectedOutput->categoryPrimary = 100;

        $expectedOutput->categories = [200,201];

        return $expectedOutput;

    }
}
