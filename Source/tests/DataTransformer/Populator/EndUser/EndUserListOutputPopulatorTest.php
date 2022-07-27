<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Populator\EndUser;

use App\DataTransformer\Populator\EndUser\EndUserListOutputPopulator;
use App\Dto\EndUser\EndUserListOutputDto;
use App\Entity\EndUser;
use App\Service\EndUser\EndUserManager;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EndUserListOutputPopulatorTest extends TestCase
{
    /** @var EndUserManager|MockObject */
    private EndUserManager|MockObject $endUserService;

    private EndUserListOutputPopulator $populator;

    protected function setUp(): void
    {
        $this->endUserService = $this->createMock(EndUserManager::class);
        $this->populator = new EndUserListOutputPopulator($this->endUserService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->populator,
            $this->endUserService,
        );
    }

    public function testPopulate(): void
    {
        $source = $this->createMock(EndUser::class);
        $target = new EndUserListOutputDto();

        $date = $this->createMock(Carbon::class);

        $source->expects(self::once())->method('getStatus')->willReturn('Active');
        $source->expects(self::once())->method('getFirstname')->willReturn('first-name');
        $source->expects(self::once())->method('getLastname')->willReturn('last-name');
        $source->expects(self::once())->method('getDateOfBirth')->willReturn($date);
        $source->expects(self::once())->method('getRegistrationDate')->willReturn($date);
        $source->expects(self::once())->method('getGender')->willReturn('male');
        $source->expects(self::once())->method('getId')->willReturn(123);

        $this->endUserService
            ->expects(self::once())
            ->method('prepareCustomFields')
            ->with($source)
            ->willReturn([])
        ;

        $date
            ->expects(self::once())
            ->method('format')
            ->with('Y-m-d')
            ->willReturn('2020-02-12')
        ;

        $this->populator->populate($source, $target);
    }
}
