<?php

namespace App\Dto\Faq;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Faq')]
class FaqDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Question',
            'example' => 'What is the company\'s activity?',
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $question;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Answer',
            'example' => 'Information Technology',
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $answer;
}
