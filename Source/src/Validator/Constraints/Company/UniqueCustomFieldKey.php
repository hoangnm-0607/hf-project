<?php

declare(strict_types=1);

namespace App\Validator\Constraints\Company;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueCustomFieldKey extends Constraint
{
    public const IS_NOT_UNIQUE_KEY = 'IS_NOT_UNIQUE_KEY';

    /** @var array<string, string> */
    protected static $errorNames = [
        self::IS_NOT_UNIQUE_KEY => self::IS_NOT_UNIQUE_KEY,
    ];

    public string $message = 'is_not_unique_key';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
