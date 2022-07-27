<?php

namespace App\Dto\EndUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'EndUser Output')]
final class EndUserOutputDto extends EndUserListOutputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Private Email',
            'example' => 'cliff.burton@gmail.com',
            'minLength' => 5,
            'maxLength' => 254,
            'type' => 'string',
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(min: 5, max: 254)]
    #[Assert\Email( mode: 'strict')]
    public ?string $privateEmail;

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
            'description' => 'Phone Number',
            'example' => '+4966555555',
            'maxLength' => 30,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 30)]
    #[Assert\Type('string')]
    public ?string $phoneNumber;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'User Image',
            'example' => 'https://link-to-user-image.jpg',
            'maxLength' => 254,
            'type' => 'string'
        ]
    ])]
    #[Assert\Length(max: 254)]
    #[Assert\Type('string')]
    public ?string $userImage = null;
}
