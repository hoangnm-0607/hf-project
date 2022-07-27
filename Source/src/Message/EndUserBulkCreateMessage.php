<?php

declare(strict_types=1);

namespace App\Message;

class EndUserBulkCreateMessage implements SqsAWSMessageInterface
{
    public const NAME = 'end_user_bulk_create_message';

    private int $companyId;
    private string $confirmationId;
    private ?string $userEmail;

    public function __construct(int $companyId, string $confirmationId, ?string $userEmail = null)
    {
        $this->companyId = $companyId;
        $this->confirmationId = $confirmationId;
        $this->userEmail = $userEmail;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getConfirmationId(): string
    {
        return $this->confirmationId;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getData(): ?array
    {
        return ['companyId' => $this->companyId, 'confirmationId' => $this->confirmationId, 'email' => $this->userEmail];
    }
}
