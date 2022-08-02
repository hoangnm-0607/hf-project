<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Populator\CAS;

use App\DataTransformer\Populator\CAS\CasPopulatorInterface;
use App\DataTransformer\Populator\CAS\CASPopulatorResolver;
use App\Dto\CAS\CasDtoInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;

final class CASPopulatorResolverTest extends TestCase
{
    private CasPopulatorInterface|MockObject $populator;

    private CASPopulatorResolver $resolver;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(CasPopulatorInterface::class);
        $this->resolver = new CASPopulatorResolver([$this->populator]);
    }

    protected function tearDown(): void
    {
        unset(
            $this->populator,
            $this->resolver,
        );
    }

    public function testPopulate(): void
    {
        $company = $this->createMock(Company::class);

        $this->populator
            ->expects(self::once())
            ->method('isSupport')
            ->with($company)
            ->willReturn(true)
        ;

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populator
            ->expects(self::once())
            ->method('populate')
            ->with($company)
            ->willReturn($dto)
        ;

        $return = $this->resolver->populate($company);
        self::assertSame($return, $dto);
    }

    public function testPopulateException(): void
    {
        $company = $this->createMock(Company::class);

        $this->populator
            ->expects(self::once())
            ->method('isSupport')
            ->with($company)
            ->willReturn(false)
        ;

        $this->populator
            ->expects(self::never())
            ->method('populate')
            ->with($company)
        ;

        $this->expectException(\LogicException::class);

        $this->resolver->populate($company);
    }
}
