<?php

declare(strict_types=1);

namespace Tests\Validator\Constraints\EndUser;

use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Exception\Validator\UnexpectedConstraintException;
use App\Repository\EndUser\EndUserRepository;
use App\Validator\Constraints\EndUser\UniqueUserEmail;
use App\Validator\Constraints\EndUser\UniqueUserEmailValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Tests\Validator\DummyConstraint;

final class UniqueUserEmailValidatorTest extends TestCase
{
    /** @var ExecutionContextInterface|MockObject */
    private ExecutionContextInterface|MockObject $context;

    /** @var EndUserRepository|MockObject */
    private EndUserRepository|MockObject $userRepository;

    private UniqueUserEmailValidator $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->userRepository = $this->createMock(EndUserRepository::class);
        $this->validator = new UniqueUserEmailValidator($this->userRepository);
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

        $this->validator->validate(new \stdClass(), new UniqueUserEmail);
    }

    public function testValidator(): void
    {
        $constraint = $this->createMock(UniqueUserEmail::class);
        $dto = $this->createMock(EndUserInputDto::class);
        $dto->businessEmail = 'f-name@gmail.com';
        $dto->privateEmail= 'l-name@gmail.com';

        $endUser = $this->createMock(EndUser::class);

        $endUser
            ->expects(self::atLeast(2))
            ->method('isEqual')
            ->with($dto)
            ->willReturn(false)
        ;

        $this->userRepository
            ->expects(self::atLeast(2))
            ->method('findOneByEmail')
            ->withConsecutive([$dto->privateEmail], [$dto->businessEmail])
            ->willReturn($endUser)
        ;

        $constrainViolationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $constrainViolationBuilder
            ->expects(self::atLeast(2))
            ->method('setCode')
            ->with('IS_NOT_UNIQUE_EMAIL')
            ->willReturnSelf()
        ;

        $constrainViolationBuilder
            ->expects(self::atLeast(2))
            ->method('atPath')
            ->withConsecutive(['privateEmail'], ['businessEmail'])
            ->willReturnSelf()
        ;

        $constrainViolationBuilder
            ->expects(self::atLeast(2))
            ->method('addViolation')
        ;
        $this->context
            ->expects(self::atLeast(2))
            ->method('buildViolation')
            ->willReturn($constrainViolationBuilder)
        ;

        $this->validator->validate($dto, $constraint);
    }
}
