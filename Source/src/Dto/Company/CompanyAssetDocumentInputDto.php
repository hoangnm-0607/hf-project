<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\VPP\Assets\AssetUploadDto;
use App\Helper\ConstHelper;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Asset Document Input')]
class CompanyAssetDocumentInputDto extends AssetUploadDto
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Document language',
            'example' => 'en',
            'type' => 'string',
            'maxLength' => 2,
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 2)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ConstHelper::LANGUAGE_SUPPORT)]
    public string $language;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Description of the document',
            'example' => 'Contains setup instructions',
            'type' => 'string',
            'maxLength' => 255,
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $description;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Id of the CCP file category',
            'example' => '123',
            'type' => 'integer',
            'nullable' => false,
        ]
    ])]
    #[Assert\Type('integer')]
    #[Assert\NotNull]
    public int $categoryId;
}
