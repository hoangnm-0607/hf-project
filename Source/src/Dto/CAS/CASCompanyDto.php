<?php

namespace App\Dto\CAS;

class CASCompanyDto implements CasDtoInterface
{
    public int $pimcoreId;

    public string $status = 'active';

    public string $name;

    public string $street;

    public string $number;

    public string $postalCode;

    public string $city;

    public string $country;

    public int $createdAt;

    public ?int $deletedAt = null;

    public ?int $contractStart = null;

    public ?int $contractEnd = null;

    public array $services = [];
}
