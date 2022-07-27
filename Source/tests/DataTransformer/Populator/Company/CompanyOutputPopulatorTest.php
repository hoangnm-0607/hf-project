<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Populator\Company;

use App\DataTransformer\Populator\Company\CompanyOutputPopulator;
use App\Dto\Company\CompanyOutputDto;
use App\Entity\Company;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CompanyOutputPopulatorTest extends TestCase
{
    private CompanyOutputPopulator $populator;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    protected function setUp(): void
    {
        $this->companyService = $this->createMock(CompanyService::class);

        $this->populator = new CompanyOutputPopulator($this->companyService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->companyService,
            $this->populator,
        );
    }

    public function testPopulate(): void
    {
        $source = $this->createMock(Company::class);
        $target = new CompanyOutputDto();
        $target->number = '111';
        $target->status = 'blocked';

        $source->expects(self::once())->method('getId')->willReturn(123);
        $source->expects(self::once())->method('getStatus')->willReturn('Active');
        $source->expects(self::once())->method('getCasCompanyId')->willReturn(812312);
        $source->expects(self::once())->method('getCity')->willReturn('city');
        $source->expects(self::once())->method('getCountry')->willReturn('country');
        $source->expects(self::once())->method('getName')->willReturn('name');
        $source->expects(self::once())->method('getNumber')->willReturn('345');
        $source->expects(self::once())->method('getStreet')->willReturn('street');
        $source->expects(self::once())->method('getZip')->willReturn('11-612');

        $this->populator->populate($source, $target);
        self::assertSame('345', $target->number);
        self::assertSame('Active', $target->status);
    }
}
