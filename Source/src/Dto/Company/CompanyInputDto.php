<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Input')]
final class CompanyInputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Name',
            'example' => 'My company',
            'maxLength' => 190,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public ?string $name = null;

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
    public ?string $country = null;

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
    public ?string $city = null;

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
    public ?string $zip = null;

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
    public ?string $street = null;

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
    public ?string $number = null;
}
