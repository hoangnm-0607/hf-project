<?php

declare(strict_types=1);

namespace App\Validator\Constraints\EndUser;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueUserEmail extends Constraint
{
    public const IS_NOT_UNIQUE_EMAIL = 'IS_NOT_UNIQUE_EMAIL';

    /** @var array<string, string> */
    protected static $errorNames = [
        self::IS_NOT_UNIQUE_EMAIL => self::IS_NOT_UNIQUE_EMAIL,
    ];

    public string $message = 'is_not_unique_email';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
