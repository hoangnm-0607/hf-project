<?php

namespace App\Repository\Company;

use Pimcore\Model\DataObject\CompanyFileCategory;

class CompanyFileCategoryRepository
{
    public function findOneSingleCompanyById(int $id): ?CompanyFileCategory
    {
        return CompanyFileCategory::getById($id);
    }
}
