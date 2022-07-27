<?php

declare(strict_types=1);

namespace Tests\Validator\Constraints\EndUser;

use App\Validator\Constraints\EndUser\UniqueUserByNameAndBirthDay;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

final class UniqueUserByNameAndBirthDayTest extends TestCase
{
    public function testGetTargets(): void
    {
        self::assertSame(Constraint::CLASS_CONSTRAINT, (new UniqueUserByNameAndBirthDay())->getTargets());
    }
}
