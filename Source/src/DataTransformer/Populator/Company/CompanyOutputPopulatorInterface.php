<?php

namespace App\DataTransformer\Populator\Company;

use App\Dto\Company\CompanyOutputDto;
use App\Entity\Company;

interface CompanyOutputPopulatorInterface
{
    public function populate(Company $source, CompanyOutputDto $target): CompanyOutputDto;
}
