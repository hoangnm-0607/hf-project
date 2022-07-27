<?php

namespace Tests\DataTransformer;

use App\DataTransformer\EventVppOutputDataTransformer;
use App\DataTransformer\Populator\EventsVpp\EventsVppOutputPopulatorInterface;
use App\Dto\VPP\Events\EventOutputDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\SingleEvent;
use stdClass;

class EventVppOutputDataTransformerTest extends TestCase
{
    private MockObject|EventsVppOutputPopulatorInterface $populator;
    private EventVppOutputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(EventsVppOutputPopulatorInterface::class);
        $this->dataTransformer = new EventVppOutputDataTransformer([$this->populator]);
    }

    public function testSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new SingleEvent(), EventOutputDto::class);
        self::assertTrue($isSupports);
    }

    public function testNotSupportsTransformation()
    {
        $isSupports = $this->dataTransformer->supportsTransformation(new SingleEvent(), stdClass::class);
        self::assertFalse($isSupports);
    }

    public function testTransform()
    {
        $data = new EventOutputDto();
        $eventMock = $this->createMock(SingleEvent::class);

        $this->populator->method('populate')->with($eventMock)->willReturn($data);

        $result = $this->dataTransformer->transform($eventMock, EventOutputDto::class);

        self::assertEquals($data, $result);
    }
}
