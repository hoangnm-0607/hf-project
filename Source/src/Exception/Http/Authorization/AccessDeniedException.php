<?php

declare(strict_types=1);

namespace App\Exception\Http\Authorization;

use Symfony\Component\Security\Core\Exception\AccessDeniedException as SymfonyAccessDeniedException;

class AccessDeniedException extends SymfonyAccessDeniedException
{
}
