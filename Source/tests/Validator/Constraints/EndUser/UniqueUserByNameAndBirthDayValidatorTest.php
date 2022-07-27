<?php

declare(strict_types=1);

namespace Tests\Validator\Constraints\EndUser;

use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Exception\Validator\UnexpectedConstraintException;
use App\Repository\EndUser\EndUserRepository;
use App\Validator\Constraints\EndUser\UniqueUserByNameAndBirthDay;
use App\Validator\Constraints\EndUser\UniqueUserByNameAndBirthDayValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Tests\Validator\DummyConstraint;

final class UniqueUserByNameAndBirthDayValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private ExecutionContextInterface|MockObject $context;

    /** @var EndUserRepository|MockObject */
    private EndUserRepository|MockObject $userRepository;

    private UniqueUserByNameAndBirthDayValidator $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->userRepository = $this->createMock(EndUserRepository::class);
        $this->validator = new UniqueUserByNameAndBirthDayValidator($this->userRepository);
        $this->validator->initialize($this->context);
    }

    protected function tearDown(): void
    {
        unset(
            $this->userRepository,
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

        $this->validator->validate(new \stdClass(), new UniqueUserByNameAndBirthDay);
    }

    public function testValidator(): void
    {
        $constraint = $this->createMock(UniqueUserByNameAndBirthDay::class);
        $dto = $this->createMock(EndUserInputDto::class);
        $dto->firstName = 'f-name';
        $dto->lastName= 'l-name';
        $dto->dateOfBirth = new \DateTime();

        $endUser = $this->createMock(EndUser::class);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneByNameAndBirthDay')
            ->with($dto->firstName, $dto->lastName, $dto->dateOfBirth)
            ->willReturn($endUser)
        ;

        $constrainViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $constrainViolationBuilder
            ->expects(self::once())
            ->method('setCode')
            ->with('IS_NOT_UNIQUE_NAME_AND_BIRTHDATE')
            ->willReturnSelf()
        ;

        $constrainViolationBuilder
            ->expects(self::once())
            ->method('atPath')
            ->with('firstName')
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
