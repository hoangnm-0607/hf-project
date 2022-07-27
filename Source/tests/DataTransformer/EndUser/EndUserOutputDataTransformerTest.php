<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use App\DataTransformer\EndUser\EndUserOutputDataTransformer;
use App\DataTransformer\Populator\EndUser\EndUserOutputPopulatorInterface;
use App\Dto\EndUser\EndUserOutputDto;
use App\Entity\EndUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EndUserOutputDataTransformerTest extends TestCase
{
    /** @var EndUserOutputPopulatorInterface|MockObject */
    private EndUserOutputPopulatorInterface|MockObject $populator;

    private EndUserOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(EndUserOutputPopulatorInterface::class);

        $this->transformer = new EndUserOutputDataTransformer([$this->populator]);
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
        yield [$this->createMock(EndUser::class), EndUserOutputDto::class, true];
        yield [$this->createMock(EndUser::class), \stdClass::class, false];
        yield [null, EndUserOutputDto::class, false];
    }

    public function testTransform(): void
    {
        $object = $this->createMock(EndUser::class);

        $this->populator
            ->expects(self::once())
            ->method('populate')
            ->with($object, self::isInstanceOf(EndUserOutputDto::class))
        ;

        $result =$this->transformer->transform($object, 'some');
        self::assertInstanceOf(EndUserOutputDto::class, $result);
    }
}
