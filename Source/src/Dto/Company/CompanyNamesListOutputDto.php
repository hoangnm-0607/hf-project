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

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'City',
            'example' => 'Berlin',
            'maxLength' => 190,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public string $city;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Zip',
            'example' => '61-611',
            'maxLength' => 10,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 10)]
    #[Assert\Type('string')]
    public string $zip;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Street',
            'example' => 'Storkower Str.',
            'maxLength' => 190,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public string $street;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Number',
            'example' => '185',
            'maxLength' => 190,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public string $number;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Country',
            'example' => 'Germany',
            'maxLength' => 190,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public string $country;
}
