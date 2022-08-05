<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Company;

use App\DataTransformer\Company\CompanyNamesListOutputDataTransformer;
use App\Dto\Company\CompanyNamesListOutputDto;
use App\Entity\Company;
use PHPUnit\Framework\TestCase;

final class CompanyNamesListOutputDataTransformerTest extends TestCase
{
    private CompanyNamesListOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new CompanyNamesListOutputDataTransformer();
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
        );
    }

    /**
     * @param mixed  $dto
     * @param string $to
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation($dto, string $to, bool $supportResult): void
    {
        $result = $this->transformer->supportsTransformation($dto, $to, []);
        self::assertSame($supportResult, $result);
    }

    public function dataProviderSupportTransformation(): iterable
    {
        yield [$this->createMock(Company::class), CompanyNamesListOutputDto::class, true];
        yield [$this->createMock(Company::class), \stdClass::class, false];
        yield [null, CompanyNamesListOutputDto::class, false];
    }

    public function testTransform(): void
    {
        $company = $this->createMock(Company::class);
        $company
            ->expects(self::once())
            ->method('getId')
            ->willReturn(777)
        ;

        $company
            ->expects(self::once())
            ->method('getName')
            ->willReturn('CompanyName')
        ;

        $company
            ->expects(self::once())
            ->method('getStreet')
            ->willReturn('Osterdeich')
        ;

        $company
            ->expects(self::once())
            ->method('getNumber')
            ->willReturn('42')
        ;

        $company
            ->expects(self::once())
            ->method('getZip')
            ->willReturn('12345')
        ;

        $company
            ->expects(self::once())
            ->method('getCity')
            ->willReturn('Bremen')
        ;

        $company
            ->expects(self::once())
            ->method('getCountry')
            ->willReturn('DE')
        ;

        $response = $this->transformer->transform($company, CompanyNamesListOutputDto::class);
        self::assertInstanceOf(CompanyNamesListOutputDto::class, $response);
        self::assertSame(777, $response->companyId);
        self::assertSame('CompanyName', $response->companyName);
        self::assertSame('Osterdeich', $response->street);
        self::assertSame('42', $response->number);
        self::assertSame('12345', $response->zip);
        self::assertSame('Bremen', $response->city);
        self::assertSame('DE', $response->country);
    }
}
