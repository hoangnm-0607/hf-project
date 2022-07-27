<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyNamesListOutputDto;
use App\Entity\Company;

class CompanyNamesListOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param Company $object
     * @param string  $to
     * @param array   $context
     *
     * @return CompanyNamesListOutputDto
     */
    public function transform($object, string $to, array $context = []): CompanyNamesListOutputDto
    {
        $target = new CompanyNamesListOutputDto();

        $target->companyId = $object->getId();
        $target->companyName = $object->getName();

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceOf Company && CompanyNamesListOutputDto::class === $to;
    }
}
