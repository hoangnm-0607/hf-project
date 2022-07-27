<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Company;

use App\DataTransformer\Company\CompanyOutputDataTransformer;
use App\DataTransformer\Populator\Company\CompanyOutputPopulatorInterface;
use App\Dto\Company\CompanyOutputDto;
use App\Entity\Company;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CompanyOutputDataTransformerTest extends TestCase
{
    /** @var CompanyOutputPopulatorInterface|MockObject */
    private CompanyOutputPopulatorInterface|MockObject $populator;

    private CompanyOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(CompanyOutputPopulatorInterface::class);

        $this->transformer = new CompanyOutputDataTransformer([$this->populator]);
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
            $this->populator,
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
        yield [$this->createMock(Company::class), CompanyOutputDto::class, true];
        yield [$this->createMock(Company::class), \stdClass::class, false];
        yield [null, CompanyOutputDto::class, false];
    }

    public function testTransform(): void
    {
        $object = $this->createMock(Company::class);

        $this->populator
            ->expects(self::once())
            ->method('populate')
            ->with($object, self::isInstanceOf(CompanyOutputDto::class))
        ;

        $result =$this->transformer->transform($object, 'some');
        self::assertInstanceOf(CompanyOutputDto::class, $result);
    }
}
