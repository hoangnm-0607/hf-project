<?php

declare(strict_types=1);

namespace App\Controller\System;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\System\ServerTimeDto;
use Symfony\Component\HttpFoundation\Request;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'method' => Request::METHOD_GET,
            'controller' => self::class,
            'output' => ServerTimeDto::class,
            'read' => false,
            'deserialize' => false,
            'path' => '/server-time',
            'openapi_context' => [
                'summary' => 'Get server time',
                'description' => 'Get server time',
            ],
        ],
    ],
    itemOperations: [],
    shortName: 'System',
    attributes: [],
    formats: ['json'],
    normalizationContext: ['allow_extra_attributes' => false],
)]
class ServerTimeController
{
    public function __invoke(): ServerTimeDto
    {
        return new ServerTimeDto();
    }
}
