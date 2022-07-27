<?php

namespace Tests\DataTransformer;

use App\DataTransformer\DatedPartnerProfileOutputDataTransformer;
use App\DataTransformer\Populator\PartnerProfile\PartnerProfileOutputPopulator;
use App\Dto\DatedPartnerProfileDto;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class PartnerProfileOutputDataTransformerTest extends TestCase
{

    private DatedPartnerProfileOutputDataTransformer $partnerProfileOutputDataTransformer;
    private PartnerProfileOutputPopulator|MockObject $populatorMock;

    protected function setUp(): void
    {
        $this->populatorMock                       = $this->createMock(PartnerProfileOutputPopulator::class);
        $this->partnerProfileOutputDataTransformer = new DatedPartnerProfileOutputDataTransformer([$this->populatorMock]);
    }

    public function testSupportsTransformationPartnerProfile()
    {
        $supports = $this->partnerProfileOutputDataTransformer->supportsTransformation(new PartnerProfile(), DatedPartnerProfileDto::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->partnerProfileOutputDataTransformer->supportsTransformation(new stdClass(), DatedPartnerProfileDto::class);

        self::assertFalse($supports);
    }

    public function testTransform()
    {
        $data = new PartnerProfileDto();
        $partnerProfileMock = $this->createMock(PartnerProfile::class);

        $this->populatorMock->method('populate')->with($partnerProfileMock)->willReturn($data);

        $result = $this->partnerProfileOutputDataTransformer->transform($partnerProfileMock, PartnerProfileDto::class);

        self::assertEquals($data, $result);
    }
}
