<?php

declare(strict_types=1);

namespace App\DBAL\Types;

class EndUserStatusType
{
    public const INVITED = 'invited';
    public const APPLIED = 'applied';
    public const CONFIRMED = 'confirmed';
    public const PENDING = 'pending';
    public const ELIGIBLE = 'eligible';
    public const ACTIVE = 'active';
    public const PAUSED = 'paused';
    public const INACTIVE = 'inactive';
    public const UNASSIGNED = 'unassigned';
    public const DELETED = 'deleted';
    public const DENIED = 'denied';

    public const EXPORT_LIST = [
        self::ACTIVE,
        self::INACTIVE,
        self::PAUSED,
        self::PENDING,
    ];

    public const CHOICES = [
        self::INVITED,
        self::APPLIED,
        self::CONFIRMED,
        self::PENDING,
        self::ELIGIBLE,
        self::ACTIVE,
        self::PAUSED,
        self::INACTIVE,
        self::UNASSIGNED,
        self::DELETED,
        self::DENIED,
    ];

    public const POSSIBLE_TO_ACTIVATE = [
        self::PENDING,
    ];
}
