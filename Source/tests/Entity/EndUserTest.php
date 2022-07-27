<?php

declare(strict_types=1);

namespace Tests\Entity;

use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use PHPUnit\Framework\TestCase;

final class EndUserTest extends TestCase
{
    public function testIsEqualTrue(): void
    {
        $user = new EndUser();
        $user->setId(123);

        $dto = $this->createMock(EndUserInputDto::class);
        $dto
            ->expects(self::once())
            ->method('getId')
            ->willReturn(123)
        ;

        self::assertTrue($user->isEqual($dto));
    }

    public function testIsEqualFalse(): void
    {
        $user = new EndUser();
        $user->setId(321);

        $dto = $this->createMock(EndUserInputDto::class);
        $dto
            ->expects(self::once())
            ->method('getId')
            ->willReturn(123)
        ;

        self::assertFalse($user->isEqual($dto));
    }

    /**
     * @param string|null $key
     * @param string      $status
     * @param bool        $waitResult
     *
     * @dataProvider permittedDataProvider
     */
    public function testIsPermittedToActivate(?string $key, string $status, bool $waitResult): void
    {
        $user = new EndUser();
        $user->setActivationKey($key);
        $user->setStatus($status);

        $result = $user->isPermittedToActivate();
        self::assertSame($waitResult, $result);
    }

    public static function permittedDataProvider(): iterable
    {
        yield [null, 'active', false];
        yield ['key', 'active', false];
        yield ['key', 'pending', true];

    }
}
