<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Service\InMemoryUserReaderService;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractBaseVoter extends Voter
{
    protected InMemoryUserReaderService $inMemoryUserReaderService;

    public function __construct(InMemoryUserReaderService $inMemoryUserReaderService)
    {
        $this->inMemoryUserReaderService = $inMemoryUserReaderService;
    }

    protected function checkRow(?string $value, string $key, array $row, bool $strict = true): bool
    {
        return (null === $value && !$strict) || (isset($row[$key]) && $row[$key] === $value);
    }
}
