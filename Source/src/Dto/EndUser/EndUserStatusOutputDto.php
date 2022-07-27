<?php

namespace App\Dto\EndUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\DBAL\Types\EndUserStatusType;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'EndUser Status Output')]
class EndUserStatusOutputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Status',
            'example' => 'active',
            'enum' => EndUserStatusType::CHOICES,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    public ?string $status;

    /**
     * @var string[]
     */
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Possible status transactions',
            'type' => 'array',
            'example' => ['fullName' => 'Clifford Lee Burton'],
            'additionalProperties' => ['type' => 'string']
        ]
    ])]
    #[Assert\Type('array')]
    public array $transitions = [];
}
