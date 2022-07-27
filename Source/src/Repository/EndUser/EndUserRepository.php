<?php

namespace App\Repository\EndUser;

use App\DBAL\Types\EndUserStatusType;
use Carbon\Carbon;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\EndUser;
use Pimcore\Model\DataObject\EndUser\Listing;

class EndUserRepository
{
    public function findOneById(string|int $id): ?EndUser
    {
        return EndUser::getById((int) $id);
    }

    public function findOneByNameAndBirthDay(string $firstName, string $lastName, string|\DateTime $birthDay): ?EndUser
    {
        $listing = new Listing();
        $listing
            ->filterByFirstname($firstName)
            ->filterByLastname($lastName)
            ->filterByDateOfBirth((new Carbon($birthDay))->getTimestamp())
            ->setUnpublished(true)
            ->setLimit(1)
        ;

        return $listing->current() ?: null;
    }

    public function findOneByEmail(?string $email): ?EndUser
    {
        $listing = new Listing();
        $listing
            ->setCondition('BusinessEmail = ? OR PrivateEmail = ?', [$email, $email])
            ->setUnpublished(true)
            ->setLimit(1)
        ;

        return $listing->current() ?: null;
    }

    public function findByCompanyForExportList(Company $company): Listing
    {
        $listing = new Listing();
        $listing
            ->filterByCompany($company)
            ->setCondition('Status IN (?)', [EndUserStatusType::EXPORT_LIST])
        ;

        return $listing;
    }
}
