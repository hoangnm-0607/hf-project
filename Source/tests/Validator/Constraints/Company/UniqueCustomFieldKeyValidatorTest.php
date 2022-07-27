<?php

declare(strict_types=1);

namespace Tests\Validator\Constraints\Company;

use App\Dto\Company\CompanyCustomFieldDto;
use App\Entity\Company;
use App\Exception\Validator\UnexpectedConstraintException;
use App\Service\Company\CompanyService;
use App\Validator\Constraints\Company\UniqueCustomFieldKey;
use App\Validator\Constraints\Company\UniqueCustomFieldKeyValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\CompanyCustomField;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Tests\Validator\DummyConstraint;

final class UniqueCustomFieldKeyValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private ExecutionContextInterface|MockObject $context;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    private UniqueCustomFieldKeyValidator $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);

        $this->companyService = $this->createMock(CompanyService::class);
        $this->validator = new UniqueCustomFieldKeyValidator($this->companyService);
        $this->validator->initialize($this->context);
    }

    protected function tearDown(): void
    {
        unset(
            $this->companyService,
            $this->validator,
            $this->context,
        );
    }

    public function testValidateIncorrectConstraintClass(): void
    {
        $this->context
            ->expects(self::never())
            ->method('buildViolation')
        ;

        $this->expectException(UnexpectedConstraintException::class);
        $this->expectExceptionMessageMatches('/^Object of class .* is not instance of .*$/');

        $this->validator->validate('test', new DummyConstraint());
    }

    public function testValidateNotDto(): void
    {
        $this->context
            ->expects(self::never())
            ->method('buildViolation')
        ;

        $this->validator->validate(new \stdClass(), new UniqueCustomFieldKey);
    }

    public function testValidator(): void
    {
        $constraint = $this->createMock(UniqueCustomFieldKey::class);
        $dto = $this->createMock(CompanyCustomFieldDto::class);
        $dto->key = 'balance';

        $companyCustomField = $this->createMock(CompanyCustomField::class);
        $company = $this->createMock(Company::class);

        $dto
            ->expects(self::once())
            ->method('getCompany')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getCompanyCustomFields')
            ->willReturn([$companyCustomField])
        ;

        $this->companyService
            ->expects(self::once())
            ->method('findCustomFieldByKey')
            ->with([$companyCustomField], $dto->key)
            ->willReturn($companyCustomField)
        ;

        $constrainViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $constrainViolationBuilder
            ->expects(self::once())
            ->method('setCode')
            ->with('IS_NOT_UNIQUE_KEY')
            ->willReturnSelf()
        ;

        $constrainViolationBuilder
            ->expects(self::once())
            ->method('atPath')
            ->with('key')
            ->willReturnSelf()
        ;

        $constrainViolationBuilder
            ->expects(self::once())
            ->method('addViolation')
        ;
        $this->context
            ->expects(self::once())
            ->method('buildViolation')
            ->willReturn($constrainViolationBuilder)
        ;

        $this->validator->validate($dto, $constraint);
    }
}
