<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\EndUser\EndUserStatusInputDataTransformer;
use App\Dto\EndUser\EndUserStatusInputDto;
use App\Entity\EndUser;
use App\Security\AuthorizationAssertHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Exception\LogicException;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;

final class EndUserStatusInputDataTransformerTest extends TestCase
{
    /** @var Registry|MockObject */
    private Registry|MockObject $workflowRegistry;

    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private EndUserStatusInputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->workflowRegistry = $this->createMock(Registry::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->transformer = new EndUserStatusInputDataTransformer();
        $this->transformer->setWorkflowRegistry($this->workflowRegistry);
        $this->transformer->setValidator($this->validator);
        $this->transformer->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->transformer,
            $this->authorizationAssertHelper,
            $this->workflowRegistry,
        );
    }

    /**
     * @param mixed  $dto
     * @param string $to
     * @param array  $context
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation($dto, string $to, array $context, bool $supportResult): void
    {
        $result = $this->transformer->supportsTransformation($dto, $to, $context);
        self::assertSame($supportResult, $result);
    }

    public static function dataProviderSupportTransformation(): iterable
    {
        yield [new EndUserStatusInputDto(), EndUser::class, ['item_operation_name' => 'update-status.as_manager'], true];
        yield [new EndUserStatusInputDto(), EndUser::class, ['item_operation_name' => 'get'], false];
        yield [new EndUserStatusInputDto(), \stdClass::class, ['item_operation_name' => 'update'], false];
        yield [null, EndUser::class, ['item_operation_name' => 'update-status.as_manager', 'input' => ['class' => EndUserStatusInputDto::class]], true];
    }

    public function testTransform(): void
    {
        $endUser = $this->createMock(EndUser::class);
        $dto = $this->createMock(EndUserStatusInputDto::class);

        $dto->transaction = 'to_block';

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto, ['groups' => ['Default', 'to_block']])
        ;

        $companyId = 777;
        $company = $this->createMock(Company::class);
        $endUser
            ->expects(self::once())
            ->method('getCompany')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getId')
            ->willReturn($companyId)
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyAdmin')
            ->with($companyId)
        ;

        $workflow = $this->createMock(WorkflowInterface::class);

        $this->workflowRegistry
            ->expects(self::once())
            ->method('get')
            ->with($endUser)
            ->willReturn($workflow)
        ;

        $workflow
            ->expects(self::once())
            ->method('can')
            ->with($endUser, $dto->transaction)
            ->willReturn(true)
        ;

        $workflow
            ->expects(self::once())
            ->method('apply')
            ->with($endUser, $dto->transaction, ['dto' => $dto])
        ;

        $endUser
            ->expects(self::once())
            ->method('save')
        ;

        $this->transformer->transform($dto, EndUser::class, ['object_to_populate' => $endUser]);
    }

    public function testTransformNotCan(): void
    {
        $endUser = $this->createMock(EndUser::class);
        $dto = $this->createMock(EndUserStatusInputDto::class);

        $dto->transaction = 'to_block';

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto, ['groups' => ['Default', 'to_block']])
        ;

        $companyId = 777;
        $company = $this->createMock(Company::class);
        $endUser
            ->expects(self::once())
            ->method('getCompany')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getId')
            ->willReturn($companyId)
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyAdmin')
            ->with($companyId)
        ;

        $workflow = $this->createMock(WorkflowInterface::class);

        $this->workflowRegistry
            ->expects(self::once())
            ->method('get')
            ->with($endUser)
            ->willReturn($workflow)
        ;

        $workflow
            ->expects(self::once())
            ->method('can')
            ->with($endUser, $dto->transaction)
            ->willReturn(false)
        ;

        $workflow
            ->expects(self::never())
            ->method('apply')
        ;

        $this->expectException(BadRequestHttpException::class);

        $this->transformer->transform($dto, EndUser::class, ['object_to_populate' => $endUser]);
    }

    public function testTransformNotApply(): void
    {
        $endUser = $this->createMock(EndUser::class);
        $dto = $this->createMock(EndUserStatusInputDto::class);

        $dto->transaction = 'to_block';

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto, ['groups' => ['Default', 'to_block']])
        ;

        $companyId = 777;
        $company = $this->createMock(Company::class);
        $endUser
            ->expects(self::once())
            ->method('getCompany')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getId')
            ->willReturn($companyId)
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyAdmin')
            ->with($companyId)
        ;

        $workflow = $this->createMock(WorkflowInterface::class);

        $this->workflowRegistry
            ->expects(self::once())
            ->method('get')
            ->with($endUser)
            ->willReturn($workflow)
        ;

        $workflow
            ->expects(self::once())
            ->method('can')
            ->with($endUser, $dto->transaction)
            ->willReturn(true)
        ;

        $e = $this->createMock(LogicException::class);

        $workflow
            ->expects(self::once())
            ->method('apply')
            ->with($endUser, $dto->transaction, ['dto' => $dto])
            ->willThrowException($e)
        ;

        $endUser
            ->expects(self::once())
            ->method('getId')
        ;

        $this->expectException(BadRequestHttpException::class);

        $this->transformer->transform($dto, EndUser::class, ['object_to_populate' => $endUser]);
    }
}
