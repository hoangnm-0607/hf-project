<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Company;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\Company\CompanyInputDataTransformer;
use App\Dto\Company\CompanyInputDto;
use App\Entity\Company;
use App\Security\AuthorizationAssertHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CompanyDataTransformerTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;
    /** @var AuthorizationAssertHelper|MockObject  */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private CompanyInputDataTransformer $dataTransformer;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->dataTransformer = new CompanyInputDataTransformer();
        $this->dataTransformer->setAuthorizationAssertHelper($this->authorizationAssertHelper);
        $this->dataTransformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->dataTransformer,
            $this->authorizationAssertHelper,
            $this->validator,
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
        $result = $this->dataTransformer->supportsTransformation($dto, $to, $context);
        self::assertSame($supportResult, $result);
    }

    public static function dataProviderSupportTransformation(): iterable
    {
        yield [new CompanyInputDto(), Company::class, ['item_operation_name' => 'update.as_admin'], true];
        yield [new CompanyInputDto(), Company::class, ['item_operation_name' => 'get'], false];
        yield [new CompanyInputDto(), \stdClass::class, ['item_operation_name' => 'update'], false];
        yield [null, Company::class, ['item_operation_name' => 'update.as_admin', 'input' => ['class' => CompanyInputDto::class]], true];
    }

    public function testTransform(): void
    {
        $dto = new CompanyInputDto();
        $company = $this->createMock(Company::class);
        $company
            ->expects(self::once())
            ->method('getId')
            ->willReturn(12345)
        ;

        $company
            ->expects(self::once())
            ->method('save')
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyAdmin')
            ->with(12345)
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto)
        ;

        $result = $this->dataTransformer->transform($dto, Company::class, ['object_to_populate' => $company]);
        self::assertSame($company, $result);
    }
}
