<?php

declare(strict_types=1);

namespace Tests\Dto\Company;

use App\Dto\Company\CompanyCustomFieldDto;
use App\Entity\Company;
use PHPUnit\Framework\TestCase;

final class CompanyCustomFieldDtoTest extends TestCase
{
    public function testConstructor(): void
    {
        $dto = new CompanyCustomFieldDto();
        $company = $this->createMock(Company::class);
        $dto->setCompany($company);

        self::assertSame($company, $dto->getCompany());
    }
}
