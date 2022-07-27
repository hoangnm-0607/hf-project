<?php

namespace App\Message;

class TestMessage implements SqsPimcoreMessageInterface
{
    private ?array $data;

    public const NAME = 'test_message';

    public function __construct(?array $data)
    {
        $this->data = $data;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}
