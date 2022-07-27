<?php

declare(strict_types=1);

namespace Tests\Controller\Company;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use App\Controller\Company\EndUserBulkUploadController;
use App\Dto\EndUser\EndUserBulkUploadFileDto;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\InMemoryUserReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File as UploadFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class EndUserBulkUploadControllerTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $service;

    private InMemoryUserReaderService|MockObject $inMemoryUserReaderService;

    private EndUserBulkUploadController $controller;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->service = $this->createMock(EndUserBulkUploadService::class);
        $this->inMemoryUserReaderService = $this->createMock(InMemoryUserReaderService::class);

        $this->controller = new EndUserBulkUploadController($this->service, $this->inMemoryUserReaderService);
        $this->controller->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->service,
            $this->controller,
            $this->inMemoryUserReaderService,
        );
    }

    public function testInvoke(): void
    {
        $request = $this->createMock(Request::class);
        $companyId = 777;

        $file = $this->createMock(UploadFile::class);

        $fileBag = $this->createMock(FileBag::class);
        $fileBag
            ->expects(self::once())
            ->method('get')
            ->with('file')
            ->willReturn($file)
        ;

        $request->files = $fileBag;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn([])
        ;

        $data = $this->createMock(EndUserBulkUploadFileDto::class);
        $data->code = 201;

        $userId = '2b82e514-cbb4-4ee8-ac8d-b81c4b993394';

        $this->service
            ->expects(self::once())
            ->method('analyzeFile')
            ->with($file, $companyId, $userId)
            ->willReturn($data)
        ;

        $this->inMemoryUserReaderService
            ->expects(self::once())
            ->method('getExtraField')
            ->with('id')
            ->willReturn($userId)
        ;

        $response = $this->controller->__invoke($request, $companyId);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(201, $response->getStatusCode());
    }

    public function testInvokeValidationException(): void
    {
        $request = $this->createMock(Request::class);
        $companyId = 777;

        $file = $this->createMock(UploadFile::class);

        $fileBag = $this->createMock(FileBag::class);
        $fileBag
            ->expects(self::once())
            ->method('get')
            ->with('file')
            ->willReturn($file)
        ;

        $request->files = $fileBag;

        $error = $this->createMock(ConstraintViolationInterface::class);
        $error
            ->expects(self::once())
            ->method('getMessage')
            ->willReturn('error')
        ;

        $this->validator
            ->expects(self::once())
            ->method('validate')
            ->willReturn([$error])
        ;

        $this->service
            ->expects(self::never())
            ->method('analyzeFile')
        ;

        $this->expectException(ValidationException::class);

        $this->controller->__invoke($request, $companyId);
    }
}
