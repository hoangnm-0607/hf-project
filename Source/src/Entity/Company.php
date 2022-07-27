<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Company\EndUserBulkCancelController;
use App\Controller\Company\EndUserBulkCreateController;
use App\Controller\Company\EndUserBulkGetResultController;
use App\Controller\Company\EndUserBulkTemplateController;
use App\Controller\Company\EndUserBulkGetFileController;
use App\Controller\Company\EndUserBulkUploadController;
use App\Controller\Company\CompanyEndUserListController;
use App\Dto\Company\CompanyAssetDocumentInputDto;
use App\Dto\Company\CompanyAssetsOutputDto;
use App\Dto\Company\CompanyInputDto;
use App\Dto\Company\CompanyNamesListOutputDto;
use App\Dto\Company\CompanyCustomFieldDto;
use App\Dto\Company\CompanyOutputDto;
use App\Dto\EndUser\EndUserBulkUploadFileDto;
use App\Dto\EndUser\EndUserListOutputDto;
use App\Helper\ConstHelper;
use Pimcore\Model\DataObject\Company as DataObjectCompany;
use Symfony\Component\HttpFoundation\Request;

#[ApiResource(
    collectionOperations: [
        'get-current-user-company-list'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/names',
            'output' => CompanyNamesListOutputDto::class,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get current user company list.',
                'description' => 'Get current user company list.',
            ],
        ],
        'get-company-end-users'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/endusers/list',
            'requirements' => ['companyId' => '\d+'],
            'output' => EndUserListOutputDto::class,
            'controller' => CompanyEndUserListController::class,
            'input' => false,
            'formats' => [
                'json',
                'csv' => ['text/csv', 'application/csv'],
                'pdf' => ['application/pdf'],
            ],
            'openapi_context' => [
                'summary' => 'Get company user list.',
                'description' => 'Get company user list.',
                'parameters' => [
                    Company::COMPANY_ID,
                    ConstHelper::QUERY_LANGUAGE,
                    [
                        'name' => 'limit',
                        'type' => 'integer',
                        'in' => 'query',
                        'required' => false,
                        'description' => 'Pagination limit for page',
                        'example' => 10,
                        'schema' => ['type' => 'integer'],
                    ],
                ],
            ],
        ],
        'get-company-custom-fields'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/custom-fields',
            'requirements' => ['companyId' => '\d+'],
            'output' => CompanyCustomFieldDto::class,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get company custom fields list.',
                'description' => 'Get company custom fields list.',
                'parameters' => [
                    Company::COMPANY_ID,
                ],
            ],
        ],
        'get-bulk-upload-list'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/endusers/bulk-upload/list',
            'requirements' => ['companyId' => '\d+'],
            'output' => EndUserBulkUploadFileDto::class,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get bulk upload files list for company.',
                'description' => 'Get bulk upload files list for company.',
                'parameters' => [Company::COMPANY_ID],
            ],
        ],
    ],
    itemOperations: [
        'get-company-document-assets'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/assets',
            'requirements' => ['companyId' => '\d+'],
            'output' => CompanyAssetsOutputDto::class,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get company assets list.',
                'description' => 'Get company assets list.',
                'parameters' => [
                    Company::COMPANY_ID,
                    ConstHelper::QUERY_LANGUAGE,
                ],
            ],
        ],
        'put-company-document-asset'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_PUT,
            'path' => '/{companyId}/assets',
            'requirements' => ['companyId' => '\d+'],
            'output' => CompanyAssetsOutputDto::class,
            'input' => CompanyAssetDocumentInputDto::class,
            'openapi_context' => [
                'summary' => 'Add company asset.',
                'description' => 'Add company asset.',
                'parameters' => [
                    Company::COMPANY_ID,
                ],
            ],
        ],
        'add-company-custom-field'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_PATCH,
            'path' => '/{companyId}/custom-fields',
            'requirements' => ['companyId' => '\d+'],
            'output' => CompanyCustomFieldDto::class,
            'input' => CompanyCustomFieldDto::class,
            'openapi_context' => [
                'summary' => 'Add company custom field.',
                'description' => 'Add company custom field.',
                'parameters' => [
                    Company::COMPANY_ID,
                ],
            ],
        ],
        'file-bulk-upload'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_POST,
            'path' => '/{companyId}/endusers/bulk-upload',
            'requirements' => ['companyId' => '\d+'],
            'controller' => EndUserBulkUploadController::class,
            'output' => EndUserBulkUploadFileDto::class,
            'deserialize' => false,
            'input' => false,
            'input_formats' => ['multipart' => ['multipart/form-data']],
            'openapi_context' => [
                'summary' => 'Upload csv file with end-users for a given company-id',
                'description' => 'Upload csv file with end-users for a given company-id',
                'consumes' => ['multipart/form-data'],
                'parameters' => [
                    [
                        'name' => 'file',
                        'in' => 'formData',
                        'required' => true,
                        'type' => 'file',
                        'description' => 'The file to upload',
                    ],
                    Company::COMPANY_ID,
                ],
            ],
        ],
        'get-bulk-template'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/endusers/bulk-template',
            'requirements' => ['companyId' => '\d+'],
            'controller' => EndUserBulkTemplateController::class,
            'output' => false,
            'deserialize' => false,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get csv template for end-users for a given companyId',
                'description' => 'Get csv template for end-users for a given companyId',
                'parameters' => [
                    Company::COMPANY_ID,
                ],
            ],
        ],
        'create-bulk-upload'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_PUT,
            'path' => '/{companyId}/endusers/bulk-upload/{confirmationId}',
            'requirements' => ['companyId' => '\d+', 'confirmationId' => '\S{32}'],
            'controller' => EndUserBulkCreateController::class,
            'output' => false,
            'deserialize' => false,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Create end-users from file a given confirmationId',
                'description' => 'Create end-users from file a given confirmationId',
                'parameters' => [
                    Company::COMPANY_ID,
                    Company::CONFIRMATION_ID,
                ],
            ],
        ],
        'cancel-bulk-upload'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_DELETE,
            'path' => '/{companyId}/endusers/bulk-upload/{confirmationId}',
            'requirements' => ['companyId' => '\d+', 'confirmationId' => '\S{32}'],
            'controller' => EndUserBulkCancelController::class,
            'output' => false,
            'deserialize' => false,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Cancel end-users upload from file a given confirmationId',
                'description' => 'Cancel end-users upload from file a given confirmationId',
                'parameters' => [
                    Company::COMPANY_ID,
                    Company::CONFIRMATION_ID,
                ],
            ],
        ],
        'get-bulk-upload'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/endusers/bulk-upload/{confirmationId}',
            'requirements' => ['companyId' => '\d+', 'confirmationId' => '\S{32}'],
            'controller' => EndUserBulkGetFileController::class,
            'output' => false,
            'deserialize' => false,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get end-users upload file data',
                'description' => 'Get end-users upload file data',
                'parameters' => [
                    Company::COMPANY_ID,
                    Company::CONFIRMATION_ID,
                ],
            ],
        ],
        'get-bulk-upload'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}/endusers/bulk-upload/{confirmationId}/result',
            'requirements' => ['companyId' => '\d+', 'confirmationId' => '\S{32}'],
            'controller' => EndUserBulkGetResultController::class,
            'output' => false,
            'deserialize' => false,
            'input' => false,
            'openapi_context' => [
                'summary' => 'Get end-users upload file result',
                'description' => 'Get end-users upload file result',
                'parameters' => [
                    Company::COMPANY_ID,
                    Company::CONFIRMATION_ID,
                ],
            ],
        ],
        'get'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{companyId}',
            'requirements' => ['companyId' => '\d+'],
            'output' => CompanyOutputDto::class,
            'openapi_context' => [
                'summary' => 'Retrieves a company resource for a given company-id',
                'description' => 'Retrieves a company resource for a given company-id',
                'parameters' => [
                    Company::COMPANY_ID,
                ],
            ],
        ],
        'update'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_PATCH,
            'path' => '/{companyId}',
            'requirements' => ['companyId' => '\d+'],
            'input' => CompanyInputDto::class,
            'openapi_context' => [
                'summary' => 'Update a company resource for a given company-id',
                'description' => 'Update a company resource for a given company-id',
                'parameters' => [Company::COMPANY_ID],
            ],
        ],
    ],
    shortName: 'Company',
    attributes: [],
    formats: ['json'],
    normalizationContext: ['allow_extra_attributes' => false],
    output: CompanyOutputDto::class,
    routePrefix: '/companies'
)]
class Company extends DataObjectCompany
{
    public const CONFIRMATION_ID = [
        'name' => 'confirmationId',
        'type' => 'string',
        'in' => 'path',
        'required' => true,
        'description' => 'Confirmation file ID',
        'example' => '1c620910a39f6f8ddfc78a25aeebe529',
        'schema' => ['type' => 'string'],
    ];

    public const COMPANY_ID = [
        'name' => 'companyId',
        'type' => 'integer',
        'in' => 'path',
        'required' => true,
        'description' => 'Id of the company',
        'example' => '123456',
        'schema' => ['type' => 'integer'],
    ];

    #[ApiProperty(identifier: true)]
    public ?string $companyId = null;
}
