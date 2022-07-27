<?php

namespace App\DataTransformer\Populator\EndUser;

use App\DataProvider\Helper\AssetHelper;
use App\Dto\EndUser\EndUserOutputDto;
use App\Entity\EndUser;
use App\Service\EndUser\EndUserManager;

class EndUserOutputPopulator implements EndUserOutputPopulatorInterface
{
    private AssetHelper $assetHelper;
    private EndUserManager $endUserService;

    public function __construct(AssetHelper $assetHelper, EndUserManager $endUserService)
    {
        $this->assetHelper = $assetHelper;
        $this->endUserService = $endUserService;
    }

    public function populate(EndUser $source, EndUserOutputDto $target): EndUserOutputDto
    {
        $target->id = $source->getId();
        $target->status = $source->getStatus();
        $target->userLocked = $source->getUserLocked();
        $target->phoneNumber = $source->getPhoneNumber();
        $target->firstName = $source->getFirstname();
        $target->lastName = $source->getLastname();
        $target->businessEmail = $source->getBusinessEmail();
        $target->privateEmail = $source->getPrivateEmail();
        $target->dateOfBirth = $source->getDateOfBirth()?->format('Y-m-d');
        $target->registrationDate = $source->getRegistrationDate();
        $target->gender = $source->getGender();
        $target->userImage = $this->assetHelper->getAssetUrl($source->getUserImage());
        $target->customFields = $this->endUserService->prepareCustomFields($source);

        return $target;
    }
}
