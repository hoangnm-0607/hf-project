<?php

namespace App\DataTransformer\Populator\CAS;

use App\Dto\CAS\CasDtoInterface;

interface CasPopulatorInterface
{
    public function populate($entity): CasDtoInterface;

    public function isSupport($entity): bool;
}
