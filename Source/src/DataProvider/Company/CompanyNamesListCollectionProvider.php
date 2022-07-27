<?php

declare(strict_types=1);

namespace App\DataProvider\Company;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Service\Company\CompanyService;
use App\Service\InMemoryUserReaderService;

class CompanyNamesListCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private CompanyService $companyService;
    private InMemoryUserReaderService $inMemoryUserReaderService;

    public function __construct(CompanyService $companyService, InMemoryUserReaderService $inMemoryUserReaderService)
    {
        $this->companyService = $companyService;
        $this->inMemoryUserReaderService = $inMemoryUserReaderService;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $decodedToken = $this->inMemoryUserReaderService->getUserIdentifier();

        if (!\is_array($decodedToken)) {
            return [];
        }

        $companyIds = [];

        foreach ($decodedToken as $row) {
            if (isset($row['companyId'])) {
                $companyIds[] = (int) $row['companyId'];
            }
        }

        return !(empty($companyIds)) ? $this->companyService->findAllByIds($companyIds) : [];
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Company::class === $resourceClass && 'get-current-user-company-list'.ConstHelper::AS_MANAGER === $operationName;
    }
}
