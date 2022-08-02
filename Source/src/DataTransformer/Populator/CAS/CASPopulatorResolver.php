<?php

declare(strict_types=1);

namespace App\DataTransformer\Populator\CAS;

use App\Dto\CAS\CasDtoInterface;

class CASPopulatorResolver
{
    /** @var iterable|CasPopulatorInterface[]  */
    private iterable $populates;

    public function __construct(iterable $populates)
    {
        $this->populates = $populates;
    }

    public function populate($entity): CasDtoInterface
    {
        return $this->getPopulator($entity)->populate($entity);
    }

    private function getPopulator($entity): CasPopulatorInterface
    {
        foreach ($this->populates as $populate) {
            if ($populate->isSupport($entity)) {
                return $populate;
            }
        }

        throw new \LogicException('unsupported entity for populates');
    }
}
