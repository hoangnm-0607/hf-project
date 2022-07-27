<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyCustomFieldDto;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Service\Company\CompanyService;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\ValidatorTrait;
use Exception;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CompanyCustomFieldInputDataTransformer implements DataTransformerInterface
{
    use AuthorizationAssertHelperTrait;
    use ValidatorTrait;

    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @param CompanyCustomFieldDto $object
     * @param string                $to
     * @param array                 $context
     *
     * @return CompanyCustomFieldDto[]
     *
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []): iterable
    {
        /** @var Company $company */
        $company = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $object->setCompany($company);

        $this->validator->validate($object, ['groups' => ['Default', 'custom_field.create']]);

        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($company->getId());

        $newCustomField = $this->companyService->createCustomFieldFromDto($object, $company);

        $newCustomField->save();

        $customFields = $company->getCompanyCustomFields();
        $customFields[] = $newCustomField;

        $company->setCompanyCustomFields($customFields);

        $company->save();

        return $this->companyService->prepareCustomFields($company);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Company::class === $to && (
                ($data instanceof CompanyCustomFieldDto) ||
                ($context['input']['class'] ?? null) === CompanyCustomFieldDto::class
            ) && 'add-company-custom-field'.ConstHelper::AS_ADMIN === $context['item_operation_name']
        ;
    }
}
