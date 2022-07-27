<?php

declare(strict_types=1);

namespace Tests\Controller\Company;

use App\Controller\Company\EndUserBulkCancelController;
use App\Service\EndUser\EndUserBulkUploadService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class EndUserBulkCancelControllerTest extends TestCase
{
    private EndUserBulkUploadService|MockObject $service;

    private EndUserBulkCancelController $controller;

    protected function setUp(): void
    {
        $this->service = $this->createMock(EndUserBulkUploadService::class);
        $this->controller = new EndUserBulkCancelController($this->service);
    }

    protected function tearDown(): void
    {
        unset(
            $this->controller,
            $this->service,
        );
    }

    public function testInvoke(): void
    {
        $companyId = 777;
        $confirmationId = 'fssdfsdfdfdff';

        $this->service
            ->expects(self::once())
            ->method('removeEndUserUploadedFile')
            ->with($companyId, $confirmationId)
        ;

        $response = $this->controller->__invoke($companyId, $confirmationId);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(204, $response->getStatusCode());
    }
}
