<?php


namespace App\DataTransformer\Populator\EventsVpp;


use App\Dto\VPP\Events\EventOutputDto;
use Pimcore\Model\DataObject\SingleEvent;

interface EventsVppOutputPopulatorInterface
{
    public function populate(SingleEvent $source, EventOutputDto $target): EventOutputDto;
}
