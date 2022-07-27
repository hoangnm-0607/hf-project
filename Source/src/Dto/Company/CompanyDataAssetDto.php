<?php

namespace App\Dto\Company;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(shortName: 'Company Data Asset')]
class CompanyDataAssetDto extends CompanyAssetDto
{
    /**
     * @var CompanyAssetDto[]
     */
    #[ApiProperty(readableLink: true)]
    #[Assert\Type('array')]
    public array $data;
}
