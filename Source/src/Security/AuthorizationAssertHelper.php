<?php

declare(strict_types=1);

namespace App\Security;

use App\Exception\Http\Authorization\AccessDeniedException;
use App\Security\Voter\CompanyVoter;
use App\Traits\AuthorizationCheckerTrait;

class AuthorizationAssertHelper
{
    use AuthorizationCheckerTrait;

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
