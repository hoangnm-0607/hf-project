<?php


namespace App\DataTransformer\Populator\Events;


use App\Dto\AvailabilityDto;
use Pimcore\Model\DataObject\SingleEvent;

interface EventsOutputPopulatorInterface
{
    public function populate(SingleEvent $source, AvailabilityDto $target): AvailabilityDto;
}
