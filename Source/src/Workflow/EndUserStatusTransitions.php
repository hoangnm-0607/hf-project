<?php

declare(strict_types=1);

namespace App\Workflow;

final class EndUserStatusTransitions
{
    public const DENY = 'deny';
    public const ACCEPT = 'accept';
    public const CHANGE_PENDING_START_DATE = 'change_pending_start_date';
    public const ON_PAUSE = 'on_pause';
    public const DEACTIVATE_END_DATE = 'deactivate_end_date';
    public const DEACTIVATE_START_DATE = 'deactivate_start_date';
    public const DELETE = 'delete';

    public const CHOICES = [
        self::DENY,
        self::ACCEPT,
        self::CHANGE_PENDING_START_DATE,
        self::ON_PAUSE,
        self::DEACTIVATE_END_DATE,
        self::DEACTIVATE_START_DATE,
        self::DELETE,
    ];
}
