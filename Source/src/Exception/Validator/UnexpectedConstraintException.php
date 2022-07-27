<?php

declare(strict_types=1);

namespace App\Exception\Validator;

use App\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraint;

class UnexpectedConstraintException extends InvalidArgumentException
{
    /**
     * @param Constraint $constraint
     * @param string     $expectedClass
     */
    public function __construct(Constraint $constraint, string $expectedClass)
    {
        parent::__construct(\sprintf('Object of class %s is not instance of %s', \get_class($constraint), $expectedClass));
    }
}
