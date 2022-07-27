<?php

declare(strict_types=1);

namespace App\Message;

class EndUserActivationMessage implements SqsAWSMessageInterface
{
    public const NAME = 'end_user_activation_message';

    private string $pdfFileName;
    private string $userEmail;

    public function __construct(string $pdfFileName, string $userEmail)
    {
        $this->pdfFileName = $pdfFileName;
        $this->userEmail = $userEmail;
    }

    public function getPdfFileName(): string
    {
        return $this->pdfFileName;
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
        return ['filename' => $this->getPdfFileName(), 'email' => $this->getUserEmail()];
    }
}
