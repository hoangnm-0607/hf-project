<?php

namespace App\DataTransformer\Populator\PartnerProfile;

use App\Entity\PartnerProfile;

class PartnerProfileBaseDataOutputPopulator
{
    public function populate(PartnerProfile $source, $target)
    {
        $target->studioName       = $source->getName() ?? '';
        $target->publicId         = $source->getCASPublicID() ?? '';
        $target->street           = $source->getStreet() ?? '';
        $target->streetNumber     = $source->getNumber() ?? '';
        $target->zip              = $source->getZip() ?? '';
        $target->city             = $source->getCity() ?? '';
        $target->country          = $source->getCountry() ?? '';
        $target->email            = $source->getEmail() ?? '';
        $target->phonenumber      = $source->getTelephone() ?? '';
        $target->website          = $source->getWebsite() ?? '';
        $target->hansefitCard     = $source->getHansefitCard() === 'Ja';
        $target->tags             = $source->getTags();
        $target->showOpeningTimes = $source->getShowOpeningTimes() === 'Ja';

        $target->coordLat  = $source->getGeoData()?->getLatitude();
        $target->coordLong = $source->getGeoData()?->getLongitude();

        $target->checkInApp  = $source->getCheckInApp() ?? false;
        $target->checkInCard = $source->getCheckInCard() ?? false;

        return $target;
    }
}
