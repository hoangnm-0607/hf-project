<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DataTransformer\Populator\Company\CompanyOutputPopulatorInterface;
use App\Dto\Company\CompanyOutputDto;
use App\Entity\Company;

class CompanyOutputDataTransformer implements DataTransformerInterface
{
    /** @var iterable|CompanyOutputPopulatorInterface[]  */
    private iterable $populates;

    public function __construct(iterable $populates)
    {
        $this->populates = $populates;
    }

    /**
     * @param Company $object
     * @param string  $to
     * @param array   $context
     *
     * @return CompanyOutputDto
     */
    public function transform($object, string $to, array $context = []): CompanyOutputDto
    {
        $target = new CompanyOutputDto();

        foreach ($this->populates as $populate) {
            $target = $populate->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceOf Company && CompanyOutputDto::class === $to;
    }
}
