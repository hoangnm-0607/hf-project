<?php

declare(strict_types=1);

namespace App\Service\File;

use JetBrains\PhpStorm\ArrayShape;
use Carbon\Carbon;

class UserRow
{
    private array $data;
    private ?UserRow $headerRow;

    public function __construct(array $data, ?UserRow $headerRow = null)
    {
        $this->data = $data;
        $this->headerRow = $headerRow;
    }

    #[ArrayShape([
        'firstName' => "string",
        'lastName' => "string",
        'privateEmail' => "null|string",
        'businessEmail' => "null|string",
        'phoneNumber' => "null|string",
        'gender' => "string",
        'dateOfBirth' => "null|string",
        'customFields' => "array",
    ])]
    public function toArray(): array
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'privateEmail' => $this->getPrivateEmail(),
            'businessEmail' => $this->getBusinessEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'gender' => $this->getGender(),
            'dateOfBirth' => $this->getDateOfBirth()?->format('Y-m-d'),
            'customFields' => $this->getCustomFields(),
        ];
    }

    public function getFirstName(): ?string
    {
        return $this->getStringOrNull(0);
    }

    public function getLastName(): ?string
    {
        return $this->getStringOrNull(1);
    }

    public function getPrivateEmail(): ?string
    {
        return $this->getStringOrNull(2);
    }

    public function getBusinessEmail(): ?string
    {
        return $this->getStringOrNull(3);
    }

    public function getPhoneNumber(): ?string
    {
        return $this->getStringOrNull(4);
    }

    public function getGender(): ?string
    {
        return $this->getStringOrNull(5);
    }

    public function getDateOfBirth(): ?Carbon
    {
        $dateTimeString = $this->getStringOrNull(6);

        return empty($dateTimeString) ? null : new Carbon($dateTimeString);
    }

    public function getByKey(int $key): ?string
    {
        return $this->getStringOrNull($key);
    }

    public function getCustomFields(): array
    {
        $result = [];
        $from = 7;
        $to = count($this->data);

        for ($i = $from; $i < $to; ++$i) {
            $keyValue = $this->headerRow->getByKey($i);

            if (null !== $keyValue) {
                $result[$keyValue] = $this->getByKey($i);
            }
        }

        return $result;
    }

    public function getFullNameWithBirthDate(): string
    {
        return sprintf('%s %s %s', $this->getFirstName(), $this->getLastName(), $this->getDateOfBirth()?->format('Y-m-d'));
    }

    private function getStringOrNull(int $key): ?string
    {
        $value = isset($this->data[$key]) ? trim($this->data[$key]) : null;

        return empty($value) ? null : $value;
    }
}
