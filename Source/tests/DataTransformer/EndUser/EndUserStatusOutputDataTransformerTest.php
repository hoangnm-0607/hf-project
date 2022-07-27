<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use App\DataTransformer\EndUser\EndUserStatusOutputDataTransformer;
use App\Dto\EndUser\EndUserStatusOutputDto;
use App\Entity\EndUser;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\WorkflowInterface;

final class EndUserStatusOutputDataTransformerTest extends TestCase
{
    /** @var Registry|MockObject */
    private Registry|MockObject $workflowRegistry;

    private EndUserStatusOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->workflowRegistry = $this->createMock(Registry::class);
        $this->transformer = new EndUserStatusOutputDataTransformer();
        $this->transformer->setWorkflowRegistry($this->workflowRegistry);
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
            $this->workflowRegistry,
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
        yield [$this->createMock(EndUser::class), EndUserStatusOutputDto::class, true];
        yield [$this->createMock(EndUser::class), \stdClass::class, false];
        yield [null, EndUserStatusOutputDto::class, false];
    }

    public function testTransform(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $workflow = $this->createMock(WorkflowInterface::class);

        $this->workflowRegistry
            ->expects(self::once())
            ->method('get')
            ->with($endUser)
            ->willReturn($workflow)
        ;

        $endUser
            ->expects(self::once())
            ->method('getStatus')
            ->willReturn('active')
        ;

        $transaction = $this->createMock(Transition::class);

        $workflow
            ->expects(self::once())
            ->method('getEnabledTransitions')
            ->with($endUser)
            ->willReturn([$transaction])
        ;

        $transaction
            ->expects(self::once())
            ->method('getName')
            ->willReturn('to_block')
        ;

        $dto = $this->transformer->transform($endUser, EndUserStatusOutputDto::class);
        self::assertSame('active', $dto->status);
        self::assertSame(['to_block'], $dto->transitions);
    }
}
