<?php

declare(strict_types=1);

namespace App\DataProvider\Company;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Repository\EndUser\EndUserRepository;
use App\Service\Company\CompanyService;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\RequestStackTrait;
use Pimcore\Model\DataObject\Listing;

class CompanyEndUserListCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use AuthorizationAssertHelperTrait;
    use RequestStackTrait;

    private EndUserRepository $userRepository;
    private CompanyService $companyService;
    private int $itemsPerPage;

    public function __construct(EndUserRepository $userRepository, CompanyService $companyService, int $itemsPerPage)
    {
        $this->userRepository = $userRepository;
        $this->companyService = $companyService;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $companyId = $this->requestStack->getCurrentRequest()->attributes->get('companyId');

        $this->authorizationAssertHelper->assertUserIsCompanyManagerOrAdmin($companyId);

        $company = $this->companyService->findOneOrThrowException($companyId);

        $users = $this->userRepository->findByCompanyForExportList($company);

        if (isset($context['filters']['page'])) {
            $limit = $context['filters']['limit'] ?? $this->itemsPerPage;
            $this->setPagination($users, (int) $limit, (int) $context['filters']['page']);
        }

        return $users;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Company::class === $resourceClass && 'get-company-end-users'.ConstHelper::AS_MANAGER === $operationName;
    }

    private function setPagination(Listing $listing, int $limit, int $page = 1): void
    {
        $offset = ($page - 1) * $limit;
        $listing->setOffset($offset);
        $listing->setLimit($limit);
    }
}
