<?php

declare(strict_types=1);

namespace Tests\Validator\Constraints\EndUser;

use App\Validator\Constraints\EndUser\UniqueUserEmail;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

final class UniqueUserEmailTest extends TestCase
{
    public function testGetTargets(): void
    {
        self::assertSame(Constraint::CLASS_CONSTRAINT, (new UniqueUserEmail())->getTargets());
    }
}
