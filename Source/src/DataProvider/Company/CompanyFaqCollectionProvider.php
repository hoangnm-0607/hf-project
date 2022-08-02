<?php

declare(strict_types=1);

namespace App\DataProvider\Company;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Service\Company\CompanyService;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\RequestStackTrait;

class CompanyFaqCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use AuthorizationAssertHelperTrait;
    use RequestStackTrait;

    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $companyId = $this->requestStack->getCurrentRequest()->attributes->get('companyId');

        $this->authorizationAssertHelper->assertUserIsCompanyManagerOrAdmin($companyId);

        $company = $this->companyService->findOneOrThrowException($companyId);

        return $company->getFaqs();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Company::class === $resourceClass && 'get-company-faqs'.ConstHelper::AS_MANAGER === $operationName;
    }
}
