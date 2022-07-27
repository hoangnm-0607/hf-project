<?php

declare(strict_types=1);

namespace App\Service\Company;

use App\Dto\Company\CompanyCustomFieldDto;
use App\Repository\Company\CompanyRepository;
use App\Repository\Documents\DocumentsRepository;
use App\Service\FolderService;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\CompanyCustomField;
use Pimcore\Model\DataObject\Localizedfield;
use Pimcore\Model\Document\Printpage;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyService
{
    private CompanyRepository $companyRepository;
    private DocumentsRepository $documentsRepository;
    private FolderService $folderService;
    private Filesystem $filesystem;
    private array $locales;

    public function __construct(CompanyRepository $companyRepository, FolderService $folderService, Filesystem $filesystem, DocumentsRepository $documentsRepository, array $locales)
    {
        $this->companyRepository = $companyRepository;
        $this->filesystem = $filesystem;
        $this->locales = $locales;
        $this->folderService = $folderService;
        $this->documentsRepository = $documentsRepository;
    }

    public function findEndUserActivationTemplate(?int $companyId, string $language): ?Printpage
    {
        return $this->documentsRepository->findActivationTemplate($companyId, $language);
    }

    public function createTemplateUsersFileForCompany(int $companyId): string
    {
        $company = $this->findOneOrThrowException($companyId);

        $header = ['First Name', 'Last Name', 'Private Email', 'Business Email', 'Phone number', 'Gender', 'Date of Birth'];

        $customFields = $this->prepareCustomFields($company);
        foreach ($customFields as $field) {
            $header[] = $field->key;
        }

        $fileName = $this->filesystem->tempnam('company', sprintf('company-%s.', $companyId));

        $handle = fopen($fileName, 'w');
        fputcsv($handle, $header);
        fclose($handle);

        return $fileName;
    }

    public function findOneOrThrowException(int|string $companyId): Company
    {
        $company = $this->companyRepository->findOneSingleCompanyById($companyId);
        if (!$company instanceof Company) {
            throw new NotFoundHttpException(sprintf('Company with id:%s not found', $companyId));
        }

        return $company;
    }

    public function findAllByIds(array $ids): iterable
    {
        return $this->companyRepository->findAllByIds($ids);
    }

    public function createCustomFieldFromDto(CompanyCustomFieldDto $dto, Company $company): CompanyCustomField
    {
        $entity = CompanyCustomField::create([
            'Key' => $dto->key,
            'InputType' => $dto->inputType,
            'Published' => true,
        ]);

        foreach ($dto->name as $lang => $name) {
            $entity->setName($name, $lang);
        }

        $parent = $this->folderService->getOrCreateCustomFieldsSubFolderForCompany($company);

        $entity->setParent($parent);

        return $entity;
    }

    /**
     * @param Company $source
     *
     * @return CompanyCustomFieldDto[]
     */
    public function prepareCustomFields(Company $source): array
    {
        $result = [];

        if ($customFields = $source->getCompanyCustomFields()) {
            foreach ($customFields as $customField) {
                $localized = $customField->getLocalizedfields();

                if (!$localized instanceof Localizedfield) {
                    continue;
                }

                $dto = new CompanyCustomFieldDto();

                $dto->key = $customField->getKey();

                foreach ($this->locales as $locale) {
                    if ($localized->languageExists($locale)) {
                        $dto->name[$locale] = $customField->getName($locale);
                    }
                }
                $dto->inputType = $customField->getInputType();

                $result[] = $dto;
            }
        }

        return $result;
    }

    /**
     * @param CompanyCustomField[] $customFields
     * @param string     $key
     *
     * @return CompanyCustomField|null
     */
    public function findCustomFieldByKey(array $customFields, string $key): ?CompanyCustomField
    {
        foreach ($customFields as $customField) {
            if ($customField->getKey() === $key) {
                return $customField;
            }
        }

        return null;
    }
}
