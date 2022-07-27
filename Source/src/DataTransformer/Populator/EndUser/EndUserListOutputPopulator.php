<?php

namespace App\DataTransformer\Populator\EndUser;

use App\Dto\EndUser\EndUserListOutputDto;
use App\Entity\EndUser;
use App\Service\EndUser\EndUserManager;

class EndUserListOutputPopulator implements EndUserListOutputPopulatorInterface
{
    private EndUserManager $endUserService;

    public function __construct(EndUserManager $endUserService)
    {
        $this->endUserService = $endUserService;
    }

    public function populate(EndUser $source, EndUserListOutputDto $target): EndUserListOutputDto
    {
        $target->id = $source->getId();
        $target->status = $source->getStatus();
        $target->userLocked = $source->getUserLocked();
        $target->firstName = $source->getFirstname();
        $target->lastName = $source->getLastname();
        $target->dateOfBirth = $source->getDateOfBirth()?->format('Y-m-d');
        $target->registrationDate = $source->getRegistrationDate();
        $target->gender = $source->getGender();
        $target->customFields = $this->endUserService->prepareCustomFields($source);

        return $target;
    }
}
