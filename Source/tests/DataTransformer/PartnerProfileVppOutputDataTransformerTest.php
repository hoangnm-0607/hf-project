<?php

namespace Tests\DataTransformer;

use App\DataTransformer\PartnerProfileVppOutputDataTransformer;
use App\DataTransformer\Populator\PartnerProfile\PartnerProfileVppOutputPopulator;
use App\Dto\PartnerProfileVppOutputDto;
use App\Entity\PartnerProfile;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class PartnerProfileVppOutputDataTransformerTest extends TestCase
{
    private MockObject|PartnerProfileVppOutputPopulator $populator;
    private PartnerProfileVppOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(PartnerProfileVppOutputPopulator::class);
        $this->dataTransformer = new PartnerProfileVppOutputDataTransformer([$this->populator]);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new PartnerProfile(), PartnerProfileVppOutputDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new PartnerProfile(), stdClass::class);
        self::assertFalse($isSupports);
    }

    /**
     * @throws Exception
     */
    public function testTransform()
    {
        $data = new PartnerProfileVppOutputDto();
        $partnerProfileMock = $this->createMock(PartnerProfile::class);

        $this->populator->method('populate')->with($partnerProfileMock)->willReturn($data);

        $result = $this->dataTransformer->transform($partnerProfileMock, PartnerProfileVppOutputDto::class);

        self::assertEquals($data, $result);
    }
}
