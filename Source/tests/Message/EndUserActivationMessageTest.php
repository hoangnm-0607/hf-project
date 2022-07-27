<?php

declare(strict_types=1);

namespace Tests\Message;

use App\Message\EndUserActivationMessage;
use PHPUnit\Framework\TestCase;

final class EndUserActivationMessageTest extends TestCase
{
    public function testConstructor(): void
    {
        $message = new EndUserActivationMessage('filename', 'email@gmail.com');

        self::assertSame('filename', $message->getPdfFileName());
        self::assertSame('email@gmail.com', $message->getUserEmail());
        self::assertSame('end_user_activation_message', $message->getName());
        self::assertSame(['filename' => 'filename', 'email' => 'email@gmail.com'], $message->getData());
    }
}
