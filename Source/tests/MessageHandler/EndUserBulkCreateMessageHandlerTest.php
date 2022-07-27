<?php

declare(strict_types=1);

namespace Tests\MessageHandler;

use App\Message\EndUserBulkCreateMessage;
use App\MessageHandler\EndUserBulkCreateMessageHandler;
use App\Service\EndUser\EndUserBulkUploadService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EndUserBulkCreateMessageHandlerTest extends TestCase
{
    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $service;

    private EndUserBulkCreateMessageHandler $handler;

    protected function setUp(): void
    {
        $this->service = $this->createMock(EndUserBulkUploadService::class);
        $this->handler = new EndUserBulkCreateMessageHandler($this->service);
    }

    protected function tearDown(): void
    {
        unset(
            $this->service,
            $this->handler,
        );
    }

    public function testInvoke(): void
    {
        $message = $this->createMock(EndUserBulkCreateMessage::class);

        $this->service
            ->expects(self::once())
            ->method('createUsersForCompanyFromFile')
            ->with($message)
        ;

        $this->handler->__invoke($message);
    }
}
