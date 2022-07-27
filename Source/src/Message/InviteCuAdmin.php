<?php

namespace App\Message;

use JetBrains\PhpStorm\ArrayShape;

class InviteCuAdmin implements SqsAWSMessageInterface
{
    public const NAME = 'inviteCuAdmin';

    private string $email;
    private int $companyId;
    private string $companyName;
    private string $firstname;
    private string $lastname;
    private ?string $position;

    public function getName(): string
    {
        return self::NAME;
    }

    #[ArrayShape(["email" => "string", "companyId" => "int", "companyName" => "string", "name" => "string", "surname" => "string", "position" => "null|string"])]
    public function getData(): ?array
    {
        return [
            "email" => $this->email,
            "companyId" => strval($this->companyId),
            "companyName" => $this->companyName,
            "name" => $this->firstname,
            "surname" => $this->lastname,
            "position" => $this->position,
        ];
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param int $companyId
     */
    public function setCompanyId(int $companyId): void
    {
        $this->companyId = $companyId;
    }

    /**
     * @param string $companyName
     */
    public function setCompanyName(string $companyName): void
    {
        $this->companyName = $companyName;
    }

    /**
     * @param string|null $position
     */
    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }
}
