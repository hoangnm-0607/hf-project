<?php

declare(strict_types=1);

namespace Tests\Message;

use App\Message\EndUserBulkCreateMessage;
use PHPUnit\Framework\TestCase;

final class EndUserBulkCreateMessageTest extends TestCase
{
    public function testConstructor(): void
    {
        $message = new EndUserBulkCreateMessage(123, 'qwert123', 'email@gmail.com');

        self::assertSame(123, $message->getCompanyId());
        self::assertSame('email@gmail.com', $message->getUserEmail());
        self::assertSame('qwert123', $message->getConfirmationId());
        self::assertSame('end_user_bulk_create_message', $message->getName());
        self::assertSame(['companyId' => 123, 'confirmationId' => 'qwert123', 'email' => 'email@gmail.com'], $message->getData());
    }
}
