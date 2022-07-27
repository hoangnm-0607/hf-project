<?php

declare(strict_types=1);

namespace App\Validator\Constraints\Company;

use App\Dto\Company\CompanyCustomFieldDto;
use App\Exception\Validator\UnexpectedConstraintException;
use App\Service\Company\CompanyService;
use Pimcore\Model\DataObject\CompanyCustomField;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueCustomFieldKeyValidator extends ConstraintValidator
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @param CompanyCustomFieldDto|mixed     $value
     * @param Constraint|UniqueCustomFieldKey $constraint
     *
     * @throws UnexpectedConstraintException
     */
    public function validate($value, Constraint|UniqueCustomFieldKey $constraint): void
    {
        if (!$constraint instanceof UniqueCustomFieldKey) {
            throw new UnexpectedConstraintException($constraint, UniqueCustomFieldKey::class);
        }

        if (!$value instanceof CompanyCustomFieldDto || !$company = $value->getCompany()) {
            return;
        }

        $companyCustomField = $this->companyService->findCustomFieldByKey($company->getCompanyCustomFields(), $value->key);

        if ($companyCustomField instanceof CompanyCustomField) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('key')
                ->setCode(UniqueCustomFieldKey::IS_NOT_UNIQUE_KEY)
                ->addViolation()
            ;
        }
    }
}
