<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Populator\CAS;

use App\DataTransformer\Populator\CAS\CASCompanyPopulator;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;

final class CASCompanyPopulatorTest extends TestCase
{
    private CASCompanyPopulator $populator;

    protected function setUp(): void
    {
        $this->populator = new CASCompanyPopulator();
    }

    protected function tearDown(): void
    {
        unset(
            $this->populator,
        );
    }

    public function testPopulate(): void
    {
        $company = $this->createMock(Company::class);

        $company->expects(self::once())->method('getId')->willReturn(123);
        $company->expects(self::once())->method('getStatus')->willReturn('active');
        $company->expects(self::once())->method('getName')->willReturn('name');
        $company->expects(self::once())->method('getStreet')->willReturn('street');
        $company->expects(self::once())->method('getNumber')->willReturn('number');
        $company->expects(self::once())->method('getZip')->willReturn('zip');
        $company->expects(self::once())->method('getCity')->willReturn('city');
        $company->expects(self::once())->method('getCountry')->willReturn('country');
        $company->expects(self::once())->method('getCreationDate')->willReturn(1234566);

        $this->populator->populate($company);
    }

    public function testSupportTrue(): void
    {
        $company = $this->createMock(Company::class);

        $result = $this->populator->isSupport($company);
        self::assertTrue($result);
    }

    public function testSupportFalse(): void
    {
        $std = $this->createMock(\stdClass::class);

        $result = $this->populator->isSupport($std);
        self::assertFalse($result);
    }
}
