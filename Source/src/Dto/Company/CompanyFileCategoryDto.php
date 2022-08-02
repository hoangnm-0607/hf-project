<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Custom fields')]
class CompanyFileCategoryDto
{

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Category Id',
            'example' => '12345',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    public int $id;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Name',
            'example' => 'Training',
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $name;
}
