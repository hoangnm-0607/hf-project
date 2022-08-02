<?php


namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Company\CompanyFileCategoryDto;
use App\Helper\ConstHelper;
use Pimcore\Model\DataObject\CompanyFileCategory as DataObjectCompanyFileCategory;
use Symfony\Component\HttpFoundation\Request;


#[ApiResource(
    collectionOperations: [
        'get-company-file-categories' => [
            'method' => Request::METHOD_GET,
            'input' => false,
            'output' => CompanyFileCategoryDto::class,
            'openapi_context' => [
                'summary' => 'Get company file category  list.',
                'description' => 'Get company file category  list.',
                'parameters' => [
                    ConstHelper::QUERY_LANGUAGE,
                ],
            ],
        ],
    ],
    itemOperations: [],
    shortName: 'CompanyFileCategory',
    attributes: [],
    formats: ['json'],
    normalizationContext: ['allow_extra_attributes' => false],
)]
final class CompanyFileCategory extends DataObjectCompanyFileCategory
{

    #[ApiProperty(identifier: true)]
    protected ?string $categoryId;
}
