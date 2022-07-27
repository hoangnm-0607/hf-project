<?php

namespace App\Message;

interface SqsMessageInterface
{
    public function getName(): string;

    public function getData(): ?array;
}



