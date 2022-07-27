<?php

namespace App\DataTransformer\Populator\Company;

use App\Dto\Company\CompanyOutputDto;
use App\Entity\Company;
use App\Service\Company\CompanyService;

class CompanyOutputPopulator implements CompanyOutputPopulatorInterface
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function populate(Company $source, CompanyOutputDto $target): CompanyOutputDto
    {
        $target->id = $source->getId();
        $target->casCompanyId = $source->getCasCompanyId();
        $target->city = $source->getCity();
        $target->country = $source->getCountry();
        $target->name = $source->getName();
        $target->number = $source->getNumber();
        $target->street = $source->getStreet();
        $target->status = $source->getStatus();
        $target->zip = $source->getZip();
        $target->customFields = $this->companyService->prepareCustomFields($source);

        return $target;
    }
}
