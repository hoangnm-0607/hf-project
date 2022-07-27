<?php

declare(strict_types=1);

namespace App\Helper;

class ConstHelper
{
    public const LANGUAGE_PARAM_NAME = 'language';

    public const QUERY_LANGUAGE = [
        'name' => self::LANGUAGE_PARAM_NAME,
        'type' => 'string',
        'in' => 'query',
        'required' => false,
        'description' => 'Language parameter, used for search filter',
        'example' => 'de',
        'enum' => self::LANGUAGE_SUPPORT,
        'schema' => ['type' => 'string'],
    ];

    public const AS_MANAGER = '.as_manager';
    public const AS_ADMIN = '.as_admin';

    //must be same as parameter %locales%
    public const LANGUAGE_SUPPORT = ['en', 'de'];
}
