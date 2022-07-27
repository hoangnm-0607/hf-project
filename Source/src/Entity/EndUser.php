<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EndUser\EndUserDeleteController;
use App\Controller\EndUser\EndUserResendActivationController;
use App\DBAL\Types\EndUserStatusType;
use App\Dto\EndUser\EndUserInputDto;
use App\Dto\EndUser\EndUserOutputDto;
use App\Dto\EndUser\EndUserStatusInputDto;
use App\Dto\EndUser\EndUserStatusOutputDto;
use App\Dto\VPP\Assets\AssetDto;
use App\Dto\VPP\Assets\AssetUploadDto;
use App\Helper\ConstHelper;
use Pimcore\Model\DataObject\EndUser as DataObjectEndUser;
use Symfony\Component\HttpFoundation\Request;

#[ApiResource(
    collectionOperations: [
        'create'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_POST,
            'path' => '',
            'input' => EndUserInputDto::class,
            'output' => EndUserOutputDto::class,
            'openapi_context' => [
                'summary' => 'Create an end user for a given data',
                'description' => 'Create an end user for a given data',
            ],
        ],
    ],
    itemOperations: [
        'resend-activation-email'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_POST,
            'path' => '/{endUserId}/resend-activation',
            'requirements' => ['endUserId' => '\d+'],
            'controller' => EndUserResendActivationController::class,
            'deserialize' => false,
            'input' => null,
            'output' => null,
            'openapi_context' => [
                'summary' => 'Resend activation to end-user email.',
                'description' => 'Resend activation to end-user email or get pdf.',
                'parameters' => [
                    EndUser::END_USER_ID,
                    ConstHelper::QUERY_LANGUAGE,
                ],
            ],
        ],
        'get-status'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{endUserId}/status',
            'requirements' => ['endUserId' => '\d+'],
            'output' => EndUserStatusOutputDto::class,
            'openapi_context' => [
                'summary' => 'Get end-user current status with possible transactions',
                'description' => 'Get end-user current status with possible transactions',
                'parameters' => [
                    EndUser::END_USER_ID,
                ],
            ],
        ],
        'update-status'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_PUT,
            'path' => '/{endUserId}/transition',
            'requirements' => ['endUserId' => '\d+'],
            'input' => EndUserStatusInputDto::class,
            'output' => EndUserStatusOutputDto::class,
            'openapi_context' => [
                'summary' => 'Get end-user current status with possible transactions',
                'description' => 'Get end-user current status with possible transactions',
                'parameters' => [
                    EndUser::END_USER_ID,
                ],
            ],
        ],
        'get'.ConstHelper::AS_MANAGER => [
            'method' => Request::METHOD_GET,
            'path' => '/{endUserId}',
            'requirements' => ['endUserId' => '\d+'],
            'output' => EndUserOutputDto::class,
            'openapi_context' => [
                'summary' => 'Retrieves a end user resource for a given endUserId',
                'description' => 'Retrieves a end user resource for a given endUserId',
                'parameters' => [
                    EndUser::END_USER_ID,
                    ConstHelper::QUERY_LANGUAGE,
                ],
            ],
        ],
        'update'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_PATCH,
            'path' => '/{endUserId}',
            'requirements' => ['endUserId' => '\d+'],
            'input' => EndUserInputDto::class,
            'openapi_context' => [
                'summary' => 'Update an end user resource for a given endUserId',
                'description' => 'Update an end user resource for a given endUserId',
                'parameters' => [
                    EndUser::END_USER_ID,
                ],
            ],
        ],
        'put_image'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_PUT,
            'path' => '/{endUserId}/image',
            'requirements' => ['endUserId' => '\d+'],
            'input' => AssetUploadDto::class,
            'output' => AssetDto::class,
            'openapi_context' => [
                'summary' => 'Assigns an uploaded file to pimcore',
                'description' => 'Assigns an uploaded file to pimcore',
                'parameters' => [
                    EndUser::END_USER_ID,
                ],
            ],
        ],
        'update_image'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_PATCH,
            'path' => '/{endUserId}/image',
            'requirements' => ['endUserId' => '\d+'],
            'input' => AssetDto::class,
            'openapi_context' => [
                'summary' => 'Updates the image resource.',
                'description' => 'Updates the image resource.',
                'parameters' => [
                    EndUser::END_USER_ID,
                ],
            ],
        ],
        'delete'.ConstHelper::AS_ADMIN => [
            'method' => Request::METHOD_DELETE,
            'path' => '/{endUserId}',
            'requirements' => ['endUserId' => '\d+'],
            'controller' => EndUserDeleteController::class,
            'deserialize' => false,
            'input' => null,
            'output' => null,
            'openapi_context' => [
                'summary' => 'Delete an end user resource for a given endUserId',
                'description' => 'Delete an end user resource for a given endUserId',
                'parameters' => [
                    EndUser::END_USER_ID,
                ],
            ],
        ],
    ],
    shortName: 'EndUser',
    attributes: [],
    formats: ['json'],
    input: EndUserInputDto::class,
    normalizationContext: ['allow_extra_attributes' => false],
    output: EndUserOutputDto::class,
    routePrefix: '/endusers'
)]
class EndUser extends DataObjectEndUser implements LastUsedAssertIdInterface, IdEqualInterface
{
    use LastUsedAssertIdTrait;

    public const END_USER_ID = [
        'name' => 'endUserId',
        'type' => 'integer',
        'in' => 'path',
        'required' => true,
        'description' => 'Id of the endUser',
        'example' => '654321',
        'schema' => ['type' => 'integer'],
    ];

    #[ApiProperty(identifier: true)]
    public ?string $endUserId = null;

    public function isEqual(IdEqualInterface $entity): bool
    {
        return $this->getId() === $entity->getId();
    }

    public function isPermittedToActivate(): bool
    {
        return
            is_string($this->getActivationKey())
            && in_array($this->getStatus(), EndUserStatusType::POSSIBLE_TO_ACTIVATE, true)
        ;
    }
}
