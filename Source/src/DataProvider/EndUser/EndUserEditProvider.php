<?php

declare(strict_types=1);

namespace App\DataProvider\EndUser;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\EndUser;
use App\Helper\ConstHelper;
use App\Repository\EndUser\EndUserRepository;
use App\Traits\AuthorizationAssertHelperTrait;
use Pimcore\Model\DataObject\EndUser as PimcoreEndUser;

class EndUserEditProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    use AuthorizationAssertHelperTrait;

    private EndUserRepository $userRepository;

    public function __construct(EndUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?PimcoreEndUser
    {
        $user = $this->userRepository->findOneById($id);

        if (null === $user || !$companyId = $user->getCompany()?->getId()) {
            return null;
        }

        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($companyId);

        return $user;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return EndUser::class === $resourceClass && str_ends_with($operationName, ConstHelper::AS_ADMIN);
    }
}
