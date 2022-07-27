<?php

namespace App\Repository\Company;

use Pimcore\Model\DataObject\Company;

class CompanyRepository
{
    public function findOneSingleCompanyById(int|string $id): ?Company
    {
        return Company::getById((int) $id);
    }

    public function findAllByIds(array $ids): Company\Listing
    {
        $listing = new Company\Listing();
        $listing
            ->setCondition('o_id IN (?)', [$ids])
        ;

        return $listing;
    }
}
