<?php

namespace App\DataTransformer\Populator\CAS;

use App\Dto\CAS\CASPartnerProfileDto;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject\ServicePackage;

class CASPartnerProfilePopulator
{

    public function populate(PartnerProfile $partnerProfile): CASPartnerProfileDto
    {
        $partnerProfileCasDto = new CASPartnerProfileDto();
        $partnerProfileCasDto->pimcoreId   = $partnerProfile->getId();
        $partnerProfileCasDto->name        = $partnerProfile->getKey();
        $partnerProfileCasDto->displayName = $partnerProfile->getName();
        $partnerProfileCasDto->street      = $partnerProfile->getCountry() == 'LU' ? $partnerProfile->getNumber().' '.$partnerProfile->getStreet() : $partnerProfile->getStreet().' '.$partnerProfile->getNumber();
        $partnerProfileCasDto->postalCode  = $partnerProfile->getZip();
        $partnerProfileCasDto->city        = $partnerProfile->getCity();
        $partnerProfileCasDto->country     = $partnerProfile->getCountry();
        $partnerProfileCasDto->geoCoordinates   = [
            'latitude'  => $partnerProfile->getGeoData()?->getLatitude(),
            'longitude' => $partnerProfile->getGeoData()?->getLongitude(),
        ];

        $partnerProfileCasDto->email    = $partnerProfile->getEmail();
        $partnerProfileCasDto->phone    = $partnerProfile->getTelephone();
        $partnerProfileCasDto->homepage = $partnerProfile->getWebsite();
        $partnerProfileCasDto->visibleOnMap = $partnerProfile->getStudioVisibility()  != 'Nein';
        $partnerProfileCasDto->categoryId = $partnerProfile->getPartnerCategoryPrimary()?->getId();
        $partnerProfileCasDto->packages = $this->aggregateServices($partnerProfile);

        return $partnerProfileCasDto;
    }

    private function aggregateServices(PartnerProfile $partnerProfile):array
    {
        return array_map(function(ServicePackage $package) {
            return $package->getCasId();
        }, $partnerProfile->getServicePackages());
    }

}
