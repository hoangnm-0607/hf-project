<?php

namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'assets upload')]
class AssetUploadDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Resource filename',
            'example' => 'abc.jpg',
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    public string $filename;


    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Resource Original filename which is displayed in the UI',
            'example' => 'real_abc.jpg',
            'type' => 'string'
        ]
    ])]
    #[Assert\Type('string')]
    public ?string $originalFilename = null;
}
