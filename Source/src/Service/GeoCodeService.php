<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Model\DataObject\PartnerProfile;
use Pimcore\Model\DataObject;

class GeoCodeService
{
    public function buildAddressQuery(PartnerProfile $partnerProfile): string {

        if ($partnerProfile->getCountry() == 'LU') {
            $address = $partnerProfile->getNumber() . '+' . $partnerProfile->getStreet() . '+';
        }
        else {
            $address = $partnerProfile->getStreet() . '+' . $partnerProfile->getNumber() . '+';
        }
        $address .= $partnerProfile->getZip() . '+';
        $address .= $partnerProfile->getCity() . '+';

        $valuesSingle = DataObject\Service::getOptionsForSelectField($partnerProfile, "Country");
        $country = $valuesSingle[$partnerProfile->getCountry()];
        $address .= $country;

        return str_replace('++', '+', $address);
    }

    public function geoCodeAddress(string $address): ?array
    {
        $geoCodeUrl = sprintf(
            'https://nominatim.openstreetmap.org/search?q=%s&addressdetails=1&format=json&limit=1',
            $address
        );

        $coords = null;

        $client = new Client();
        try {
            $result = $client->get($geoCodeUrl);
            if ($result->getStatusCode() === 200 && ($contents = $result->getBody()->getContents())) {
                $geoData = json_decode($contents);
                if(is_array($geoData) && !empty($geoData)) {
                    $geoDataEntry = $geoData[0];
                    if ($geoDataEntry->lat && $geoDataEntry->lon) {
                        $coords = [
                            'latitude' => $geoDataEntry->lat,
                            'longitude' => $geoDataEntry->lon
                        ];
                    }
                }
            }
        } catch (GuzzleException) {
            // shhh... do nothing
        }
        return $coords;
    }
}
