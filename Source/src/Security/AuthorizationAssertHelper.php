<?php

declare(strict_types=1);

namespace App\Security;

use App\Exception\Http\Authorization\AccessDeniedException;
use App\Security\Voter\AssetVoter;
use App\Security\Voter\CompanyVoter;
use App\Traits\AuthorizationCheckerTrait;
use Pimcore\Model\Asset;

class AuthorizationAssertHelper
{
    use AuthorizationCheckerTrait;

    public function assertUserIsFileOwner(Asset $file): void
    {
        if (!$this->authorizationChecker->isGranted(AssetVoter::OWNER, $file)) {
            throw new AccessDeniedException();
        }
    }

    public function assertUserIsCompanyManagerOrAdmin(string|int|null $companyId): void
    {
        if (
            null === $companyId
            || !(
                    $this->authorizationChecker->isGranted(CompanyVoter::ADMIN, (string) $companyId)
                    ||
                    $this->authorizationChecker->isGranted(CompanyVoter::MANAGER, (string) $companyId)
            )
        ) {
            throw new AccessDeniedException();
        }
    }

    public function assertUserIsCompanyAdmin(string|int|null $companyId): void
    {
        if (null === $companyId || !$this->authorizationChecker->isGranted(CompanyVoter::ADMIN, (string) $companyId)) {
            throw new AccessDeniedException();
        }
    }

    public function assertUserIsCompanyAnyRole(string|int|null $companyId): void
    {
        if (null === $companyId || !$this->authorizationChecker->isGranted(CompanyVoter::ANY_ROLE, (string) $companyId)) {
            throw new AccessDeniedException();
        }
    }
}
