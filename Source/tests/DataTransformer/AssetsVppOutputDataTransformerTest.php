<?php

namespace Tests\DataTransformer;

use App\DataTransformer\AssetsVppOutputDataTransformer;
use App\DataTransformer\Populator\PartnerProfile\AssetsVppOutputPopulatorInterface;
use App\Dto\VPP\Assets\AssetsVppOutputDto;
use App\Entity\PartnerProfile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class AssetsVppOutputDataTransformerTest extends TestCase
{
    private MockObject|AssetsVppOutputPopulatorInterface $populator;
    private AssetsVppOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(AssetsVppOutputPopulatorInterface::class);
        $this->dataTransformer = new AssetsVppOutputDataTransformer([$this->populator]);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new PartnerProfile(), AssetsVppOutputDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new PartnerProfile(), stdClass::class);
        self::assertFalse($isSupports);
    }

    public function testTransform()
    {
        $data = new AssetsVppOutputDto();
        $partnerProfileMock = $this->createMock(PartnerProfile::class);

        $this->populator->method('populate')->with($partnerProfileMock)->willReturn($data);

        $result = $this->dataTransformer->transform($partnerProfileMock, AssetsVppOutputDto::class);

        self::assertEquals($data, $result);
    }
}
