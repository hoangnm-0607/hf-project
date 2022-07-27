<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Company;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\Company\CompanyCustomFieldInputDataTransformer;
use App\Dto\Company\CompanyCustomFieldDto;
use App\Entity\Company;
use App\Security\AuthorizationAssertHelper;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\CompanyCustomField;

final class CompanyCustomFieldInputDataTransformerTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;
    /** @var AuthorizationAssertHelper|MockObject  */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    private CompanyCustomFieldInputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);
        $this->companyService = $this->createMock(CompanyService::class);

        $this->transformer = new CompanyCustomFieldInputDataTransformer($this->companyService);
        $this->transformer->setAuthorizationAssertHelper($this->authorizationAssertHelper);
        $this->transformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
            $this->companyService,
            $this->validator,
            $this->authorizationAssertHelper,
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
        yield [new CompanyCustomFieldDto(), Company::class, ['item_operation_name' => 'add-company-custom-field.as_admin'], true];
        yield [new CompanyCustomFieldDto(), Company::class, ['item_operation_name' => 'get'], false];
        yield [new CompanyCustomFieldDto(), \stdClass::class, ['item_operation_name' => 'update'], false];
        yield [null, Company::class, ['item_operation_name' => 'add-company-custom-field.as_admin', 'input' => ['class' => CompanyCustomFieldDto::class]], true];
    }

    public function testTransform(): void
    {
        $dto = new CompanyCustomFieldDto();
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

        $customField = $this->createMock(CompanyCustomField::class);

        $this->companyService
            ->expects(self::once())
            ->method('createCustomFieldFromDto')
            ->with($dto, $company)
            ->willReturn($customField)
        ;

        $customField
            ->expects(self::once())
            ->method('save')
        ;

        $company
            ->expects(self::once())
            ->method('getCompanyCustomFields')
            ->willReturn([])
        ;

        $company
            ->expects(self::once())
            ->method('setCompanyCustomFields')
            ->willReturn([$customField])
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->with($dto)
        ;

        $this->companyService
            ->expects(self::once())
            ->method('prepareCustomFields')
            ->with($company)
            ->willReturn(['data'])
        ;

        $result = $this->transformer->transform($dto, Company::class, ['object_to_populate' => $company]);
        self::assertSame(['data'], $result);
    }
}
