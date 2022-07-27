<?php

declare(strict_types=1);

namespace App\Dto\System;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class ServerTimeDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Date Time as object',
            'type' => 'object',
        ]
    ])]
    #[Assert\Type('datetime')]
    #[Assert\NotNull()]
    public \DateTime $object;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Date Time as formatted string (Y-m-d H:i:s)',
            'example' => '2022-07-04 15:12:11',
            'type' => 'string',
        ]
    ])]
    public string $formatted;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Date Time as timestamp',
            'example' => '1656921671',
            'type' => 'integer',
        ]
    ])]
    public int $timestamp;

    public function __construct()
    {
        $now = new \DateTime();

        $this->object = $now;
        $this->formatted = $now->format('Y-m-d H:i:s');
        $this->timestamp = $now->getTimestamp();
    }
}
