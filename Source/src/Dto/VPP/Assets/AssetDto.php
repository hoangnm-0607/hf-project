<?php

namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'asset')]
class AssetDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Pimcore assetId',
            'example' => '1234',
            'type' => 'integer'
        ]
    ])]
    #[Assert\Type('integer')]
    public ?int $assetId = null;
}
