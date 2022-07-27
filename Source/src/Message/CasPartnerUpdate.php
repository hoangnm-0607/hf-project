<?php

namespace App\Message;

use JetBrains\PhpStorm\ArrayShape;

class CasPartnerUpdate implements SqsCasMessageInterface
{
    public const NAME = 'partner_update';

    private int $timestamp;
    private int $partnerId;
    private int $pimcoreId;
    private string $name;
    private string $displayName;
    private string $street;
    private string $postalCode;
    private string $city;
    private string $country;
    private ?float $latitude;
    private ?float $longitude;
    private string $phone;
    private string $email;
    private string $homepage;
    private int $categoryId;
    private array $packagesAdd;
    private array $packagesDelete;
    private bool $locked;
    private bool $visibleOnMap;

    public function __construct()
    {
        $this->timestamp = time();
    }

    #[ArrayShape(["timestamp" => "int", "partnerId" => "int", "profile" => "array"])] public function getData(): ?array
    {
        return [
            "timestamp" => $this->timestamp,
            "partnerId" => $this->partnerId,
            "profile" => [
                "name" => $this->name,
                "pimcoreId" => $this->pimcoreId,
                "displayName" => $this->displayName,
                "street" => $this->street,
                "postalCode" => $this->postalCode,
                "city" => $this->city,
                "country" => $this->country,
                "geoCoordinates" => [
                    "latitude" => $this->latitude,
                    "longitude" => $this->longitude,
                ],
                "phone" => $this->phone,
                "email" => $this->email,
                "homepage" => $this->homepage,
                "categoryId" => $this->categoryId,
                "packages" => [
                    "add" => $this->packagesAdd,
                    "delete" => $this->packagesDelete,
                ],
                "locked" => $this->locked,
                "visibleOnMap" => $this->visibleOnMap,
            ]
        ];
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }


    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }


    /**
     * @param float $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }


    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $homepage
     */
    public function setHomepage(string $homepage): void
    {
        $this->homepage = $homepage;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @param array $packagesAdd
     */
    public function setPackagesAdd(array $packagesAdd): void
    {
        $this->packagesAdd = $packagesAdd;
    }

    /**
     * @param array $packagesDelete
     */
    public function setPackagesDelete(array $packagesDelete): void
    {
        $this->packagesDelete = $packagesDelete;
    }

    /**
     * @param bool $locked
     */
    public function setLocked(bool $locked): void
    {
        $this->locked = $locked;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @param int $partnerId
     */
    public function setPartnerId(int $partnerId): void
    {
        $this->partnerId = $partnerId;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }

    /**
     * @param int $pimcoreId
     */
    public function setPimcoreId(int $pimcoreId): void
    {
        $this->pimcoreId = $pimcoreId;
    }

    /**
     * @param bool $visibleOnMap
     */
    public function setVisibleOnMap(bool $visibleOnMap): void
    {
        $this->visibleOnMap = $visibleOnMap;
    }
}
