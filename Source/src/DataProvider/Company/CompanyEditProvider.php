<?php

declare(strict_types=1);

namespace App\DataProvider\Company;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Service\Company\CompanyService;
use App\Traits\AuthorizationAssertHelperTrait;
use Pimcore\Model\DataObject\Company as PimcoreCompany;

class CompanyEditProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    use AuthorizationAssertHelperTrait;

    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?PimcoreCompany
    {
        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($id);

        return $this->companyService->findOneOrThrowException($id);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Company::class === $resourceClass && str_ends_with($operationName, ConstHelper::AS_ADMIN);
    }
}
