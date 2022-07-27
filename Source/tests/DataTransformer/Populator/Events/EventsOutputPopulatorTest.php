<?php

namespace Tests\DataTransformer\Populator\Events;

use App\DataTransformer\Populator\Events\EventsOutputPopulator;
use App\Dto\AvailabilityDto;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\SingleEvent;

class EventsOutputPopulatorTest extends TestCase
{

    public function testPopulate(): void
    {
        $populator = new EventsOutputPopulator();
        $target    = new AvailabilityDto();

        $output = $populator->populate($this->createInput(), $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }

    private function createInput(): SingleEvent
    {
        $input = new SingleEvent();
        $input->setId(1299);
        $input->setCapacity(84);

        return $input;

    }

    #[Pure] private function createExpectedOutput(): AvailabilityDto
    {
        $output = new AvailabilityDto();
        $output->eventId = 1299;
        $output->capacity = 84;

        return $output;
    }
}
