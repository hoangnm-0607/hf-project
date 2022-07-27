<?php


namespace App\DataTransformer\Populator\Events;


use App\Dto\AvailabilityDto;
use Pimcore\Model\DataObject\SingleEvent;

class EventsOutputPopulator implements EventsOutputPopulatorInterface
{

    public function populate(SingleEvent $source, AvailabilityDto $target): AvailabilityDto
    {
        $target->eventId = $source->getId();
        $target->capacity = $source->getCapacity() ?? '';
        return $target;
    }
}
