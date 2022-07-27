<?php


namespace App\DataCollection;

use Iterator;
use Pimcore\Model\DataObject\SingleEvent;

final class EventList implements Iterator
{
    private array $availableFilterValues;
    private SingleEvent\Listing $events;

    public function __construct(SingleEvent\Listing $events, ?array $availableFilterValues = [] )
    {
        $this->events = $events;
        $this->availableFilterValues = $availableFilterValues;
    }

    /**
     * @return array
     */
    public function getAvailableFilterValues(): array
    {
        return $this->availableFilterValues;
    }

    /**
     * @param array $availableFilterValues
     */
    public function setAvailableFilterValues(array $availableFilterValues): void
    {
        $this->availableFilterValues = $availableFilterValues;
    }

    /**
     * @return SingleEvent\Listing
     */
    public function getEvents(): SingleEvent\Listing
    {
        return $this->events;
    }

    /**
     * @param SingleEvent\Listing $events
     */
    public function setEvents(SingleEvent\Listing $events): void
    {
        $this->events = $events;
    }

    public function current()
    {
        return $this->events->current();
    }

    public function next()
    {
        $this->events->next();
    }

    public function key()
    {
        return $this->events->key();
    }

    public function valid(): bool
    {
        return $this->events->valid();
    }

    public function rewind()
    {
        $this->events->rewind();
    }
}
