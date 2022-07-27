<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Helper\ConstHelper;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Assets Output')]
final class CompanyAssetsOutputDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Assets name',
            'example' => 'Documents',
            'maxLength' => 190,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 190)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $name;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Language',
            'example' => 'en',
            'maxLength' => 2,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    #[Assert\Length(max: 2)]
    #[Assert\Type('string')]
    #[Assert\Choice(choices: ConstHelper::LANGUAGE_SUPPORT)]
    public string $language;

    /**
     * @var CompanyDataAssetDto[]
     */
    #[ApiProperty(readableLink: true)]
    #[Assert\Type('array')]
    public array $data = [];
}
