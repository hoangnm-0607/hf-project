<?php

declare(strict_types=1);

namespace App\Validator\Constraints\EndUser;

use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Exception\Validator\UnexpectedConstraintException;
use App\Repository\EndUser\EndUserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserEmailValidator extends ConstraintValidator
{
    private EndUserRepository $userRepository;

    public function __construct(EndUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param EndUserInputDto|mixed      $value
     * @param Constraint|UniqueUserEmail $constraint
     *
     * @throws UnexpectedConstraintException
     */
    public function validate($value, Constraint|UniqueUserByNameAndBirthDay $constraint): void
    {
        if (!$constraint instanceof UniqueUserEmail) {
            throw new UnexpectedConstraintException($constraint, UniqueUserEmail::class);
        }

        if ($value instanceof EndUserInputDto) {
            $endUser = $this->userRepository->findOneByEmail($value->privateEmail);

            if ($endUser instanceof EndUser && !$endUser->isEqual($value)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath('privateEmail')
                    ->setCode(UniqueUserEmail::IS_NOT_UNIQUE_EMAIL)
                    ->addViolation()
                ;
            }

            $endUser = $this->userRepository->findOneByEmail($value->businessEmail);

            if ($endUser instanceof EndUser && !$endUser->isEqual($value)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath('businessEmail')
                    ->setCode(UniqueUserEmail::IS_NOT_UNIQUE_EMAIL)
                    ->addViolation()
                ;
            }
        }
    }
}
