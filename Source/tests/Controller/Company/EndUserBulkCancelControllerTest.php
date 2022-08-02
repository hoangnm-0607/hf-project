<?php

declare(strict_types=1);

namespace Tests\Controller\Company;

use App\Controller\Company\EndUserBulkCancelController;
use App\Security\AuthorizationAssertHelper;
use App\Service\EndUser\EndUserBulkUploadService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\JsonResponse;

final class EndUserBulkCancelControllerTest extends TestCase
{
    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $service;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private EndUserBulkCancelController $controller;

    protected function setUp(): void
    {
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->service = $this->createMock(EndUserBulkUploadService::class);
        $this->controller = new EndUserBulkCancelController($this->service);
        $this->controller->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->controller,
            $this->service,
            $this->authorizationAssertHelper,
        );
    }

    public function testInvoke(): void
    {
        $companyId = 777;
        $confirmationId = 'fssdfsdfdfdff';

        $asset = $this->createMock(Asset::class);

        $this->service
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
