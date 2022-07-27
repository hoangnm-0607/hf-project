<?php

namespace Tests\DataTransformer;

use App\DataTransformer\PartnerCategoryOutputDataTransformer;
use App\DataTransformer\Populator\PartnerCategory\PartnerCategoryOutputPopulatorInterface;
use App\Dto\PartnerCategoryDto;
use App\Entity\PartnerCategory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class PartnerCategoryOutputDataTransformerTest extends TestCase
{
    private PartnerCategoryOutputDataTransformer $partnerCategoryOutputDataTransformer;
    private PartnerCategoryOutputPopulatorInterface|MockObject $populatorMock;

    protected function setUp(): void
    {
        $this->populatorMock = $this->createMock(PartnerCategoryOutputPopulatorInterface::class);
        $this->partnerCategoryOutputDataTransformer = new PartnerCategoryOutputDataTransformer([$this->populatorMock]);
    }

    public function testSupportsTransformationPartnerCategory()
    {
        $supports = $this->partnerCategoryOutputDataTransformer->supportsTransformation(new PartnerCategory(), PartnerCategoryDto::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->partnerCategoryOutputDataTransformer->supportsTransformation(new stdClass(), PartnerCategoryDto::class);

        self::assertFalse($supports);
    }

    public function testTransform()
    {
        $data = new PartnerCategoryDto();
        $partnerCategoryMock = $this->createMock(PartnerCategory::class);

        $this->populatorMock->method('populate')->with($partnerCategoryMock)->willReturn($data);

        $result = $this->partnerCategoryOutputDataTransformer->transform($partnerCategoryMock, PartnerCategoryDto::class);

        self::assertEquals($data, $result);
    }
}
