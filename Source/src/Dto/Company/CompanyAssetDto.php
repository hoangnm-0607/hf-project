<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Asset')]
class CompanyAssetDto
{

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
            'description' => 'URI',
            'example' => 'https://url_to_s3.docx',
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $uri;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Description of the document',
            'example' => 'Contains setup instructions',
            'type' => 'string',
            'maxLength' => 255,
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $description;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Id of the CCP file category',
            'example' => '123',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public int $categoryId;

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
