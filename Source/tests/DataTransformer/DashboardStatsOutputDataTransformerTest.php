<?php

namespace Tests\DataTransformer;

use App\DataTransformer\DashboardStatsOutputDataTransformer;
use App\DataTransformer\Populator\Dashboard\DashboardStatsOutputPopulatorInterface;
use App\Dto\VPP\Dashboard\DashboardStatsDto;
use App\Entity\PartnerProfile;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class DashboardStatsOutputDataTransformerTest extends TestCase
{
    private MockObject|DashboardStatsOutputPopulatorInterface $populator;
    private DashboardStatsOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(DashboardStatsOutputPopulatorInterface::class);
        $this->dataTransformer = new DashboardStatsOutputDataTransformer([$this->populator]);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new PartnerProfile(), DashboardStatsDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new PartnerProfile(), stdClass::class);
        self::assertFalse($isSupports);
    }

    public function testTransform()
    {
        $data = new DashboardStatsDto();
        $partnerProfileMock = $this->createMock(PartnerProfile::class);

        $this->populator->method('populate')->with($partnerProfileMock)->willReturn($data);

        $result = $this->dataTransformer->transform($partnerProfileMock, DashboardStatsDto::class);

        self::assertEquals($data, $result);
    }
}
