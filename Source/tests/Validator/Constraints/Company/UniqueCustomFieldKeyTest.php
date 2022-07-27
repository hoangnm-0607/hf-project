<?php

declare(strict_types=1);

namespace Tests\Validator\Constraints\Company;

use App\Validator\Constraints\Company\UniqueCustomFieldKey;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;

final class UniqueCustomFieldKeyTest extends TestCase
{
    public function testGetTargets(): void
    {
        self::assertSame(Constraint::CLASS_CONSTRAINT, (new UniqueCustomFieldKey())->getTargets());
    }
}
