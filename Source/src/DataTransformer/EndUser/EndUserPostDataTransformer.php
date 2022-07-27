<?php

declare(strict_types=1);

namespace App\DataTransformer\EndUser;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Helper\ConstHelper;
use App\Service\Company\CompanyService;
use App\Service\EndUser\EndUserManager;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\ValidatorTrait;
use Exception;

class EndUserPostDataTransformer implements DataTransformerInterface
{
    use AuthorizationAssertHelperTrait;
    use ValidatorTrait;

    private CompanyService $companyService;
    private EndUserManager $endUserManager;

    public function __construct(CompanyService $companyService, EndUserManager $endUserManager)
    {
        $this->companyService = $companyService;
        $this->endUserManager = $endUserManager;
    }

    /**
     * @param EndUserInputDto $object
     * @param string          $to
     * @param array           $context
     *
     * @return EndUser
     *
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []): EndUser
    {
        $this->validator->validate($object, ['groups' => ['Default', 'end_user.create']]);
        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($object->companyId);

        $company = $this->companyService->findOneOrThrowException($object->companyId);

        $endUser = $this->endUserManager->createEndUserForCompanyFromDto($company, $object);

        $endUser->save();

        return $endUser;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return EndUser::class === $to && (
                ($data instanceof EndUserInputDto) ||
                ($context['input']['class'] ?? null) === EndUserInputDto::class
            ) && isset($context['collection_operation_name']) && 'create'.ConstHelper::AS_MANAGER === $context['collection_operation_name']
        ;
    }
}
