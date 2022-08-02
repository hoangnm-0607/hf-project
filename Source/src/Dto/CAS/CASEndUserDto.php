<?php

namespace App\Dto\CAS;

class CASEndUserDto implements CasDtoInterface
{
    public int $pimcoreId;
    public string $firstName;
    public string $lastName;
    public ?string $privateEmail;
    public ?string $businessEmail;
    public bool $userLocked;
    public string $status;
    public string $gender;
    public ?string $dateOfBirth;
    public ?string $phoneNumber;
    public ?int $companyId;
}
