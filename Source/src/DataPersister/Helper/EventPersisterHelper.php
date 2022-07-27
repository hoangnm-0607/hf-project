<?php


namespace App\DataPersister\Helper;


use Exception;
use Pimcore\Model\DataObject\SingleEvent;

class EventPersisterHelper
{
    /**
     * @throws Exception
     */
    public function savePreparedEvent(SingleEvent $event): ?SingleEvent
    {
        return $event->save();
    }

}
