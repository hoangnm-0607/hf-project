<?php

declare(strict_types=1);

namespace Tests\Controller\Company;

use App\Controller\Company\EndUserBulkTemplateController;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;

final class EndUserBulkTemplateControllerTest extends TestCase
{
    private CompanyService|MockObject $companyService;

    private EndUserBulkTemplateController $controller;

    protected function setUp(): void
    {
        $this->companyService = $this->createMock(CompanyService::class);
        $this->controller = new EndUserBulkTemplateController($this->companyService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->companyService,
            $this->controller,
        );
    }

    public function testInvoke(): void
    {
        $companyId = 777;

        $fileName = '/tmp/file00x34';
        $file = fopen($fileName, "w");
        fclose($file);

        $this->companyService
            ->expects(self::once())
            ->method('createTemplateUsersFileForCompany')
            ->with($companyId)
            ->willReturn($fileName)
        ;

        $response = $this->controller->__invoke($companyId);

        self::assertInstanceOf(BinaryFileResponse::class, $response);
        self::assertInstanceOf(File::class, $response->getFile());
        self::assertSame(Response::HTTP_OK, $response->getStatusCode());
    }
}
