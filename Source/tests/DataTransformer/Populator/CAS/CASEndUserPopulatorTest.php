<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Populator\CAS;

use App\DataTransformer\Populator\CAS\CASEndUserPopulator;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\EndUser;

final class CASEndUserPopulatorTest extends TestCase
{
    private CASEndUserPopulator $populator;

    protected function setUp(): void
    {
        $this->populator = new CASEndUserPopulator();
    }

    protected function tearDown(): void
    {
        unset(
            $this->populator,
        );
    }

    public function testPopulate(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $endUser->expects(self::once())->method('getId')->willReturn(123);
        $endUser->expects(self::once())->method('getStatus')->willReturn('active');
        $endUser->expects(self::once())->method('getFirstname')->willReturn('name');
        $endUser->expects(self::once())->method('getLastname')->willReturn('name');
        $endUser->expects(self::once())->method('getCompany')->willReturn(null);
        $endUser->expects(self::once())->method('getPrivateEmail')->willReturn('street');
        $endUser->expects(self::once())->method('getBusinessEmail')->willReturn('number');
        $endUser->expects(self::once())->method('getUserLocked')->willReturn('yes');
        $endUser->expects(self::once())->method('getGender')->willReturn('city');
        $endUser->expects(self::once())->method('getPhoneNumber')->willReturn('country');
        $endUser->expects(self::once())->method('getDateOfBirth')->willReturn(null);

        $this->populator->populate($endUser);
    }

    public function testSupportTrue(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $result = $this->populator->isSupport($endUser);
        self::assertTrue($result);
    }

    public function testSupportFalse(): void
    {
        $std = $this->createMock(\stdClass::class);

        $result = $this->populator->isSupport($std);
        self::assertFalse($result);
    }
}
