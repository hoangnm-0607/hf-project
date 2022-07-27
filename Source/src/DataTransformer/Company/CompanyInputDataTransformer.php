<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyInputDto;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\ValidatorTrait;
use Exception;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CompanyInputDataTransformer implements DataTransformerInterface
{
    use AuthorizationAssertHelperTrait;
    use ValidatorTrait;

    /**
     * @param CompanyInputDto $object
     * @param string          $to
     * @param array           $context
     *
     * @return Company
     *
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []): Company
    {
        $this->validator->validate($object);

        /** @var Company $company */
        $company = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $this->authorizationAssertHelper->assertUserIsCompanyAdmin($company->getId());

        // @todo add check and update fields
        $company->save();

        return $company;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Company::class === $to && (
                ($data instanceof CompanyInputDto) ||
                ($context['input']['class'] ?? null) === CompanyInputDto::class
            ) && 'update'.ConstHelper::AS_ADMIN === $context['item_operation_name']
        ;
    }
}
