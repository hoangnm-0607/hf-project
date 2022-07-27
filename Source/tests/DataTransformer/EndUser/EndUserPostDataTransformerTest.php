<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\EndUser\EndUserPostDataTransformer;
use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Security\AuthorizationAssertHelper;
use App\Service\Company\CompanyService;
use App\Service\EndUser\EndUserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;

final class EndUserPostDataTransformerTest extends TestCase
{
    private EndUserPostDataTransformer $transformer;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var EndUserManager|MockObject */
    private EndUserManager|MockObject $endUserManager;

    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    protected function setUp(): void
    {
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->endUserManager = $this->createMock(EndUserManager::class);
        $this->validator =$this->createMock(ValidatorInterface::class);

        $this->transformer = new EndUserPostDataTransformer($this->companyService, $this->endUserManager);
        $this->transformer->setAuthorizationAssertHelper($this->authorizationAssertHelper);
        $this->transformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->authorizationAssertHelper,
            $this->companyService,
            $this->endUserManager,
            $this->validator,
            $this->transformer,
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
        yield [new EndUserInputDto(), EndUser::class, ['collection_operation_name' => 'create.as_manager'], true];
        yield [new EndUserInputDto(), EndUser::class, ['item_operation_name' => 'get'], false];
        yield [new EndUserInputDto(), \stdClass::class, ['item_operation_name' => 'update'], false];
        yield [null, EndUser::class, ['collection_operation_name' => 'create.as_manager', 'input' => ['class' => EndUserInputDto::class]], true];
    }

    public function testTransform(): void
    {
        $dto = $this->createMock(EndUserInputDto::class);
        $dto->companyId = 777;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto, ['groups' => ['Default', 'end_user.create']])
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyAdmin')
            ->with($dto->companyId)
        ;

        $company = $this->createMock(Company::class);
        $endUser = $this->createMock(EndUser::class);

        $this->companyService
            ->expects(self::once())
            ->method('findOneOrThrowException')
            ->with($dto->companyId)
            ->willReturn($company)
        ;

        $this->endUserManager
            ->expects(self::once())
            ->method('createEndUserForCompanyFromDto')
            ->with($company, $dto)
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('save')
        ;

        $this->transformer->transform($dto, EndUser::class, ['collection_operation_name' => 'create']);
    }
}
