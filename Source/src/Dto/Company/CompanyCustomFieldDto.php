<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Company;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as AppAssert;

#[ApiResource(shortName: 'Company Custom fields')]
#[AppAssert\Company\UniqueCustomFieldKey(groups: ['custom_field.create'])]
class CompanyCustomFieldDto
{
    public const INPUT_TYPES = ['integer', 'string', 'float'];

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Key',
            'example' => 'personalNumber',
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public ?string $key = null;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Input Type',
            'example' => 'integer',
            'enum' => self::INPUT_TYPES,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\Choice(choices: CompanyCustomFieldDto::INPUT_TYPES)]
    #[Assert\NotBlank]
    public ?string $inputType = null;

    /**
     * @var array<string,string>
     */
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Contains all custom fields',
            'type' => 'object',
            'example' => [
                'en' => 'Personal Number',
                'de' => 'Persönliche Nummer',
            ],
            'additionalProperties' => ['string' => 'string'],
        ]
    ])]
    #[Assert\Type('array')]
    public array $name = [];

    private Company $company;

    #[ApiProperty(
        readable: false,
        writable: false,
    )]
    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }


    #[ApiProperty(
        readable: false,
        writable: false,
    )]
    public function getCompany(): Company
    {
        return $this->company;
    }
}
