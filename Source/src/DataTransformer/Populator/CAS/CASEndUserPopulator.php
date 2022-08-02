<?php

namespace App\DataTransformer\Populator\CAS;

use App\Dto\CAS\CASEndUserDto;
use Pimcore\Model\DataObject\EndUser;

class CASEndUserPopulator implements CasPopulatorInterface
{
    /**
     * @param EndUser $endUser
     *
     * @return CASEndUserDto
     */
    public function populate($endUser): CASEndUserDto
    {
        $dto = new CASEndUserDto();
        $dto->pimcoreId = $endUser->getId();
        $dto->status = $endUser->getStatus();
        $dto->firstName = $endUser->getFirstname();
        $dto->lastName = $endUser->getLastname();
        $dto->companyId = $endUser->getCompany()?->getCasCompanyId();
        $dto->privateEmail = $endUser->getPrivateEmail();
        $dto->businessEmail = $endUser->getBusinessEmail();
        $dto->dateOfBirth = $endUser->getDateOfBirth()?->format('Y-m-d');
        $dto->userLocked = 'yes' === $endUser->getUserLocked();
        $dto->gender = $endUser->getGender();
        $dto->phoneNumber = $endUser->getPhoneNumber();

        return $dto;
    }

    public function isSupport($entity): bool
    {
        return $entity instanceof EndUser;
    }
}
