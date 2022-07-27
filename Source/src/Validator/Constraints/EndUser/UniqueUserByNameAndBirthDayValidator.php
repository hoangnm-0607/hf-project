<?php

declare(strict_types=1);

namespace App\Validator\Constraints\EndUser;

use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Exception\Validator\UnexpectedConstraintException;
use App\Repository\EndUser\EndUserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserByNameAndBirthDayValidator extends ConstraintValidator
{
    private EndUserRepository $userRepository;

    public function __construct(EndUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param EndUserInputDto|mixed                  $value
     * @param Constraint|UniqueUserByNameAndBirthDay $constraint
     *
     * @throws UnexpectedConstraintException
     */
    public function validate($value, Constraint|UniqueUserByNameAndBirthDay $constraint): void
    {
        if (!$constraint instanceof UniqueUserByNameAndBirthDay) {
            throw new UnexpectedConstraintException($constraint, UniqueUserByNameAndBirthDay::class);
        }

        if ($value instanceof EndUserInputDto) {
            $endUser = $this->userRepository->findOneByNameAndBirthDay($value->firstName, $value->lastName, $value->dateOfBirth);

            if ($endUser instanceof EndUser && !$endUser->isEqual($value)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath('firstName')
                    ->setCode(UniqueUserByNameAndBirthDay::IS_NOT_UNIQUE_NAME_AND_BIRTHDATE)
                    ->addViolation()
                ;
            }
        }
    }
}
