<?php

declare(strict_types=1);

namespace App\Service\EndUser;

use App\DBAL\Types\EndUserStatusType;
use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Repository\EndUser\EndUserRepository;
use App\Service\Company\CompanyService;
use App\Service\FolderService;
use App\Traits\I18NServiceTrait;
use Carbon\Carbon;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\CompanyCustomField;
use Pimcore\Model\DataObject\Data\ObjectMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndUserManager
{
    use I18NServiceTrait;

    private FolderService $folderService;
    private CompanyService $companyService;
    private EndUserRepository $userRepository;

    public function __construct(FolderService $folderService, CompanyService $companyService, EndUserRepository $userRepository)
    {
        $this->folderService = $folderService;
        $this->companyService = $companyService;
        $this->userRepository = $userRepository;
    }

    public function prepareCustomFields(EndUser $source): array
    {
        $language = $this->i18NService->getLanguageFromRequest();
        $result = [];

        if ($customFields = $source->getCustomFields()) {
            foreach ($customFields as $customField) {
                $customFieldObject = $customField->getObject();
                if ($customFieldObject instanceof CompanyCustomField) {
                    $data = $customField->getData();

                    $name = $customFieldObject->getName($language) ?: $customFieldObject->getKey();
                    $result[$name] = $data['fieldValue'] ?? null;
                }
            }
        }

        return $result;
    }

    public function createEndUserForCompanyFromDto(Company $company, EndUserInputDto $dto): EndUser
    {
        $folderObject = $this->folderService->getOrCreateEndUsersFolderForCompany($company);
        $userKey = sprintf('%s %s %s', $dto->firstName, $dto->lastName, (new Carbon('now'))->format('YmdHis'));

        $companyCustomFields = $company->getCompanyCustomFields();

        $customFields = [];

        foreach ($dto->customFields as $key => $value) {
            $companyCustomField = $this->companyService->findCustomFieldByKey($companyCustomFields, $key);
            if (null === $companyCustomField) {
                continue;
            }
            $data = new ObjectMetadata('CustomFields', ['fieldValue'],  $companyCustomField);
            $data->setFieldValue($value);
            $customFields[] = $data;
        }

        $endUser = new EndUser();
        $endUser
            ->setKey($userKey)
            ->setParent($folderObject)
            ->setCompany($company)
            ->setBusinessEmail($dto->businessEmail)
            ->setPrivateEmail($dto->privateEmail)
            ->setPhoneNumber($dto->phoneNumber)
            ->setDateOfBirth(new Carbon($dto->dateOfBirth->setTime(0, 0)))
            ->setFirstname($dto->firstName)
            ->setLastname($dto->lastName)
            ->setGender($dto->gender)
            ->setStatus('invited')
            ->setUserLocked('no')
            ->setCustomFields($customFields)
            ->setPublished(true)
        ;

        return $endUser;
    }

    public function removeUser(EndUser $user): void
    {
        $folderObject = $this->folderService->getOrCreateEndUsersFolderForFolderName('Unassigned End Users');

        $user
            ->setCompany(null)
            ->setCustomFields(null)
            ->setParent($folderObject)
            ->setStatus(EndUserStatusType::DELETED)
            ->save()
        ;
    }

    public function findOneOrThrowException(int|string $id): EndUser
    {
        $user = $this->userRepository->findOneById($id);
        if (!$user instanceof \Pimcore\Model\DataObject\EndUser) {
            throw new NotFoundHttpException('User not found');
        }

        return $user;
    }
}
