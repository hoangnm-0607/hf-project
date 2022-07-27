<?php

declare(strict_types=1);

namespace App\Dto\EndUser;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\JsonResponse;

#[ApiResource(shortName: 'EndUser Bulk File Upload')]
class EndUserBulkUploadFileDto implements EndUserBulkUploadFileInterface
{
    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Errors',
            'example' => 'File is empty',
            'type' => 'string'
        ]
    ])]
    public ?string $errors = null;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Success',
            'type' => 'array'
        ]
    ])]
    public array $success = [];

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Errors array',
            'type' => 'array'
        ]
    ])]
    public array $errorsArray = [];

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'File Owner UUID',
            'example' => '2b82e514-cbb4-4ee8-ac8d-b81c4b993394',
            'minLength' => 36,
            'maxLength' => 36,
            'type' => 'string',
            'nullable' => false,
        ]
    ])]
    public ?string $owner;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Confirmation Id',
            'example' => '2b1d89c481783b3f9ee8a85e12336729',
            'minLength' => 32,
            'maxLength' => 32,
            'type' => 'string'
        ]
    ])]
    public ?string $confirmationId = null;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'File last modified datetime',
            'example' => '2022-07-01 12:07:58',
            'type' => 'string'
        ]
    ])]
    public ?string $lastModifiedAt;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'File last modified datetime as timestamp',
            'example' => '1656667214',
            'type' => 'integer'
        ]
    ])]
    public ?int $lastModifiedAtTS;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'Version',
            'example' => '214',
            'type' => 'integer'
        ]
    ])]
    public ?int $version;

    #[ApiProperty(attributes: [
        'openapi_context' => [
            'description' => 'File Path',
            'example' => 'https://path-to-file/2b1d89c481783b3f9ee8a85e12336729.json',
            'type' => 'string'
        ]
    ])]
    public ?string $filePath = null;

    public int $code = JsonResponse::HTTP_OK;
}
