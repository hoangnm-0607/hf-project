<?php

namespace App\Dto\EndUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\IdEqualInterface;
use App\Entity\IdTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

#[ApiResource(shortName: 'EndUser Input')]
#[Assert\GroupSequence(['EndUserInputDto', 'second'])]
#[AppAssert\EndUser\UniqueUserEmail]
#[AppAssert\EndUser\UniqueUserByNameAndBirthDay(groups: ['second'])]
class EndUserInputDto implements IdEqualInterface
{
    use IdTrait;

    private const GENDER = ['male', 'female', 'other', 'no-input'];

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
    #[Assert\NotBlank()]
    public ?string $firstName = null;

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
    #[Assert\NotBlank()]
    public ?string $lastName = null;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Private Email',
            'example' => 'cliff.burton@gmail.com',
            'minLength' => 5,
            'maxLength' => 254,
            'type' => 'string'
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(min: 5, max: 254)]
    #[Assert\Email( mode: 'strict')]
    public ?string $privateEmail = null;

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
    public ?string $businessEmail = null;

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
    #[Assert\NotBlank(groups: ['end_user.create'])]
    #[Assert\Choice(choices: EndUserInputDto::GENDER, groups: ['end_user.create'])]
    #[Assert\NotNull(groups: ['end_user.create'])]
    public ?string $gender = null;

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
    #[Assert\Type('datetime')]
    #[Assert\NotNull(groups: ['end_user.create'])]
    public ?\DateTime $dateOfBirth = null;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Phone Number',
            'example' => '+4966555555',
            'maxLength' => 30,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 30)]
    #[Assert\Type('string')]
    #[Assert\Regex('/^\+{0,1}\d+$/', message: 'phone_number_numbers_only')]
    public ?string $phoneNumber = null;

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
            'description' => 'Company Id',
            'example' => '12345',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    #[Assert\NotNull(groups: ['end_user.create'])]
    public ?int $companyId = null;
}
