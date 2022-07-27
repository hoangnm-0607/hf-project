<?php

namespace App\Dto\EndUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Workflow\EndUserStatusTransitions;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'EndUser Status Input')]
class EndUserStatusInputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Status',
            'example' => 'active',
            'enum' => EndUserStatusTransitions::CHOICES,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Choice(choices: EndUserStatusTransitions::CHOICES)]
    #[Assert\NotNull()]
    public string $transaction;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Start Date',
            'example' => '2022-12-11',
            'minLength' => 10,
            'maxLength' => 10,
            'type' => 'string',
            'required' => false,
        ]
    ])]
    #[Assert\Type('datetime')]
    #[Assert\NotNull(groups: [
        EndUserStatusTransitions::CHANGE_PENDING_START_DATE,
        EndUserStatusTransitions::ON_PAUSE,
        EndUserStatusTransitions::DEACTIVATE_START_DATE,
    ])]
    public ?\DateTime $startDate = null;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'End Date',
            'example' => '2023-02-10',
            'minLength' => 10,
            'maxLength' => 10,
            'type' => 'string',
            'required' => false,
        ]
    ])]
    #[Assert\Type('datetime')]
    #[Assert\NotNull(groups: [
        EndUserStatusTransitions::ON_PAUSE,
        EndUserStatusTransitions::DEACTIVATE_END_DATE,
    ])]
    public ?\DateTime $endDate = null;
}
