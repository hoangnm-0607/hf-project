<?php

namespace Tests\DataTransformer;

use App\DataTransformer\Populator\Events\EventsOutputPopulatorInterface;
use App\DataTransformer\SingleEventOutputDataTransformer;
use App\Dto\AvailabilityDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\SingleEvent;
use stdClass;

class SingleEventOutputDataTransformerTest extends TestCase
{
    private SingleEventOutputDataTransformer $singleEventOutputDataTransformer;
    private EventsOutputPopulatorInterface|MockObject $populatorMock;

    public function setUp():void
    {
        $this->populatorMock = $this->createMock(EventsOutputPopulatorInterface::class);
        $this->singleEventOutputDataTransformer = new SingleEventOutputDataTransformer([$this->populatorMock]);

    }

    public function testTransform(): void
    {
        $data = new AvailabilityDto();
        $singleEventMock = $this->createMock(SingleEvent::class);

        $this->populatorMock->method('populate')->with($singleEventMock)->willReturn($data);

        $result = $this->singleEventOutputDataTransformer->transform($singleEventMock, AvailabilityDto::class);

        self::assertEquals($data, $result);
    }

    public function testSupportsTransformationSingleEvent():void
    {
        $supports = $this->singleEventOutputDataTransformer->supportsTransformation(new SingleEvent(), AvailabilityDto::class);

        self::assertTrue($supports);
    }

    public function testNotSupportsTransformationWrongEntity():void
    {
        $supports = $this->singleEventOutputDataTransformer->supportsTransformation(new stdClass(), AvailabilityDto::class);

        self::assertFalse($supports);
    }
}
