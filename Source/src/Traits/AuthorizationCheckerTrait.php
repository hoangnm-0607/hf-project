<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait AuthorizationCheckerTrait
{
    protected AuthorizationCheckerInterface $authorizationChecker;

    #[Required]
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }
}
