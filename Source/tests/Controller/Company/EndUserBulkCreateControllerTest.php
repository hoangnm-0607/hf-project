<?php

declare(strict_types=1);

namespace Tests\Controller\Company;

use App\Controller\Company\EndUserBulkCreateController;
use App\Message\EndUserBulkCreateMessage;
use App\Security\AuthorizationAssertHelper;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\InMemoryUserReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Exception\LogicException;

final class EndUserBulkCreateControllerTest extends TestCase
{
    /** @var InMemoryUserReaderService|MockObject */
    private InMemoryUserReaderService|MockObject $inMemoryUserReaderService;

    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $bulkUploadService;

    /** @var MessageBusInterface|MockObject */
    private MessageBusInterface|MockObject $messageBus;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private EndUserBulkCreateController $controller;

    protected function setUp(): void
    {
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);
        $this->inMemoryUserReaderService = $this->createMock(InMemoryUserReaderService::class);
        $this->bulkUploadService = $this->createMock(EndUserBulkUploadService::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        $this->controller = new EndUserBulkCreateController($this->inMemoryUserReaderService, $this->bulkUploadService);
        $this->controller->setMessageBusDispatcher($this->messageBus);
        $this->controller->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->controller,
            $this->bulkUploadService,
            $this->inMemoryUserReaderService,
            $this->messageBus,
            $this->authorizationAssertHelper,
        );
    }

    public function testInvokeException(): void
    {
        $companyId = 777;
        $confirmationId = 'sdfsfdsfdfdfd';

        $this->bulkUploadService
            ->expects(self::once())
            ->method('isFileContainErrors')
            ->with($companyId, $confirmationId)
            ->willReturn(true)
        ;

        $this->expectException(LogicException::class);

        $this->controller->__invoke($companyId, $confirmationId);
    }

    public function testInvoke(): void
    {
        $companyId = 777;
        $confirmationId = 'sdfsfdsfdfdfd';

        $asset = $this->createMock(Asset::class);

        $this->bulkUploadService
            ->expects(self::once())
            ->method('findFile')
            ->with($companyId, $confirmationId)
            ->willReturn($asset)
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsFileOwner')
            ->with($asset)
        ;

        $this->bulkUploadService
            ->expects(self::once())
            ->method('isFileContainErrors')
            ->with($companyId, $confirmationId)
            ->willReturn(false)
        ;

        $this->inMemoryUserReaderService
            ->expects(self::once())
            ->method('getExtraField')
            ->with('email')
        ;

        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(EndUserBulkCreateMessage::class))
        ;

        $response = $this->controller->__invoke($companyId, $confirmationId);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(200, $response->getStatusCode());
    }
}
