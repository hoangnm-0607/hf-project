<?php

namespace App\Dto\EndUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'EndUser List Output')]
class EndUserListOutputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'End User Id',
            'example' => '12345',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    public int $id;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Status',
            'example' => 'Active',
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public ?string $status;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'User Locked',
            'example' => 'no',
            'enum' => [
                'yes',
                'no',
            ],
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    public ?string $userLocked;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'First Name',
            'example' => 'Cliff',
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public string $firstName;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Last Name',
            'example' => 'Burton',
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public string $lastName;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Gender',
            'example' => 'male',
            'minLength' => 1,
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(min:1, max: 190)]
    #[Assert\Type('string')]
    public string $gender;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Business Email',
            'example' => 'cliff.burton.work@gmail.com',
            'minLength' => 5,
            'maxLength' => 254,
            'type' => 'string'
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(min: 5, max: 254)]
    #[Assert\Email( mode: 'strict')]
    public ?string $businessEmail;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Date Of Birth (YYYY-MM-DD)',
            'example' => '1962-02-10',
            'minLength' => 10,
            'maxLength' => 10,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(min:10, max: 10)]
    #[Assert\Type('string')]
    #[Assert\Date]
    public string $dateOfBirth;

    /**
     * @var array<string,string>
     */
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Contains all custom fields',
            'type' => 'object',
            'example' => ['fullName' => 'Clifford Lee Burton'],
            'additionalProperties' => ['type' => 'string']
        ]
    ])]
    #[Assert\Type('array')]
    public array $customFields = [];

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Date Of Registration (YYYY-MM-DD)',
            'example' => '2020-02-10',
            'minLength' => 10,
            'maxLength' => 10,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(min:10, max: 10)]
    #[Assert\Type('string')]
    #[Assert\Date]
    public ?string $registrationDate;
}
