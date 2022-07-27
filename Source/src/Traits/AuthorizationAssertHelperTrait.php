<?php

declare(strict_types=1);

namespace App\Traits;

use App\Security\AuthorizationAssertHelper;
use Symfony\Contracts\Service\Attribute\Required;

trait AuthorizationAssertHelperTrait
{
    protected AuthorizationAssertHelper $authorizationAssertHelper;

    #[Required]
    public function setAuthorizationAssertHelper(AuthorizationAssertHelper $authorizationAssertHelper): void
    {
        $this->authorizationAssertHelper = $authorizationAssertHelper;
    }
}
