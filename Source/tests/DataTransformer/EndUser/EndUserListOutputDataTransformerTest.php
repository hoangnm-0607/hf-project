<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use App\DataTransformer\EndUser\EndUserListOutputDataTransformer;
use App\DataTransformer\Populator\EndUser\EndUserListOutputPopulatorInterface;
use App\Dto\EndUser\EndUserListOutputDto;
use App\Entity\EndUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EndUserListOutputDataTransformerTest extends TestCase
{
    /** @var EndUserListOutputPopulatorInterface|MockObject */
    private EndUserListOutputPopulatorInterface|MockObject $populator;

    private EndUserListOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->populator = $this->createMock(EndUserListOutputPopulatorInterface::class);

        $this->transformer = new EndUserListOutputDataTransformer([$this->populator]);
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
        yield [$this->createMock(EndUser::class), EndUserListOutputDto::class, true];
        yield [$this->createMock(EndUser::class), \stdClass::class, false];
        yield [null, EndUserListOutputDto::class, false];
    }

    public function testTransform(): void
    {
        $object = $this->createMock(EndUser::class);

        $this->populator
            ->expects(self::once())
            ->method('populate')
            ->with($object, self::isInstanceOf(EndUserListOutputDto::class))
        ;

        $result =$this->transformer->transform($object, 'some');
        self::assertInstanceOf(EndUserListOutputDto::class, $result);
    }
}
