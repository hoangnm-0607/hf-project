<?php

namespace App\Service\Company;

use App\Repository\Company\CompanyFileCategoryRepository;
use Pimcore\Model\DataObject\CompanyFileCategory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyFileCategoryService
{
    private CompanyFileCategoryRepository $companyFileCategoryRepository;

    public function __construct(CompanyFileCategoryRepository $companyFileCategoryRepository)
    {

        $this->companyFileCategoryRepository = $companyFileCategoryRepository;
    }

    public function findOneOrThrowException(int $categoryId): CompanyFileCategory
    {
        $category = $this->companyFileCategoryRepository->findOneSingleCompanyById($categoryId);
        if (!$category instanceof CompanyFileCategory) {
            throw new NotFoundHttpException(sprintf('CompanyFileCategory with id:%s not found', $categoryId));
        }

        return $category;
    }
}
