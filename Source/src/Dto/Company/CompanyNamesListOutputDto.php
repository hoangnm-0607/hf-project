<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Names List Output')]
final class CompanyNamesListOutputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Company Id',
            'example' => '12345',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    public int $companyId;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Company Name',
            'example' => 'My company',
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $companyName;
}
