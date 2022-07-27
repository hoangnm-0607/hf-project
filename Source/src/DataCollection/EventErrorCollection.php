<?php


namespace App\DataCollection;

use ArrayIterator;

final class EventErrorCollection
{
    private array $errors;
    private ArrayIterator $events;

    public function __construct(ArrayIterator $events, ?array $errors = [] )
    {
        $this->events = $events;
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @return ArrayIterator
     */
    public function getEvents(): ArrayIterator
    {
        return $this->events;
    }

    /**
     * @param ArrayIterator $events
     */
    public function setEvents(ArrayIterator $events): void
    {
        $this->events = $events;
    }
}
