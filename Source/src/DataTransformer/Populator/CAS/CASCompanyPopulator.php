<?php

namespace App\DataTransformer\Populator\CAS;

use App\Dto\CAS\CASCompanyDto;
use Pimcore\Model\DataObject\Company;

class CASCompanyPopulator implements CasPopulatorInterface
{
    /**
     * @param Company $company
     *
     * @return CASCompanyDto
     */
    public function populate($company): CASCompanyDto
    {
        $dto = new CASCompanyDto();
        $dto->pimcoreId = $company->getId();
        $dto->status = $company->getStatus();
        $dto->name = $company->getName();
        $dto->street = $company->getStreet();
        $dto->number = $company->getNumber();
        $dto->postalCode = $company->getZip();
        $dto->city = $company->getCity();
        $dto->country = $company->getCountry();
        $dto->createdAt = $company->getCreationDate();

        return $dto;
    }

    public function isSupport($entity): bool
    {
        return $entity instanceof Company;
    }
}
