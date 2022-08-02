<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyFileCategoryDto;
use App\Entity\CompanyFileCategory;
use App\Traits\I18NServiceTrait;

class CompanyFileCategoryOutputDataTransformer implements DataTransformerInterface
{
    use I18NServiceTrait;

    /**
     * @param CompanyFileCategory $object
     * @param string  $to
     * @param array   $context
     *
     * @return CompanyFileCategoryDto
     */
    public function transform($object, string $to, array $context = []): CompanyFileCategoryDto
    {
        $language = $this->i18NService->getLanguageFromRequest();

        $target = new CompanyFileCategoryDto();

        $target->id = $object->getId();
        $target->name = $object->getName($language);

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceOf CompanyFileCategory && CompanyFileCategoryDto::class === $to;
    }
}
