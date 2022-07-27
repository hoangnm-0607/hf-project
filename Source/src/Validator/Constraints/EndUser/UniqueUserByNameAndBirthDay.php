<?php

declare(strict_types=1);

namespace App\Validator\Constraints\EndUser;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueUserByNameAndBirthDay extends Constraint
{
    public const IS_NOT_UNIQUE_NAME_AND_BIRTHDATE = 'IS_NOT_UNIQUE_NAME_AND_BIRTHDATE';

    /** @var array<string, string> */
    protected static $errorNames = [
        self::IS_NOT_UNIQUE_NAME_AND_BIRTHDATE => self::IS_NOT_UNIQUE_NAME_AND_BIRTHDATE,
    ];

    public string $message = 'is_not_unique_name_and_birthdate';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
