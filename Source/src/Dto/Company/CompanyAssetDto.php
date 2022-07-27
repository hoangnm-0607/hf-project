<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Asset')]
class CompanyAssetDto
{
    private const TYPES = ['s3', 'folder'];

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Id',
            'example' => '123',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public int $id;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Type',
            'example' => 's3',
            'enum' => self::TYPES,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\Choice(choices: CompanyAssetDto::TYPES)]
    #[Assert\NotBlank]
    public string $type;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Value',
            'example' => 'https://url_to_s3.docx',
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $value;
}
