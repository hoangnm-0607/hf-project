<?php

namespace App\Dto\CAS;

class CASPartnerProfileDto implements CasDtoInterface
{
     public ?int $pimcoreId;

     public string $name;

     public string $displayName;

     public string $street;

     public string $postalCode;

     public string $city;

     public string $country;

     public array $geoCoordinates;

     public ?string $email;

     public ?string $phone;

     public ?string $homepage;

    public ?string $note;

     public bool $visibleOnMap;

     public int $categoryId;

     public array $packages;
}
