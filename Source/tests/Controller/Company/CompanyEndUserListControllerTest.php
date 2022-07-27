<?php

declare(strict_types=1);

namespace Tests\Controller\Company;

use App\Controller\Company\CompanyEndUserListController;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\File\PdfFileService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\EndUser\Listing;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CompanyEndUserListControllerTest extends TestCase
{
    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $bulkUploadService;

    /** @var PdfFileService|MockObject */
    private PdfFileService|MockObject $pdfFileService;

    private string $filename;

    private CompanyEndUserListController $controller;

    protected function setUp(): void
    {
        $this->pdfFileService = $this->createMock(PdfFileService::class);
        $this->bulkUploadService = $this->createMock(EndUserBulkUploadService::class);
        $this->controller = new CompanyEndUserListController($this->bulkUploadService, $this->pdfFileService);

        $this->filename = '/tmp/users-777.asdfdf';

        $handle = fopen($this->filename, 'w');
        fputcsv($handle, ['test']);
        fclose($handle);
    }

    protected function tearDown(): void
    {
        unlink($this->filename);

        unset(
            $this->controller,
            $this->bulkUploadService,
            $this->pdfFileService,
        );
    }

    /**
     * @param string|null $contentType
     * @param string      $responseClass
     *
     * @dataProvider dataInvokeDataProvider
     */
    public function testInvoke(?string $contentType, string $responseClass): void
    {
        $request = $this->createMock(Request::class);
        $listing = $this->createMock(Listing::class);

        $header = $this->createMock(HeaderBag::class);
        $attr = $this->createMock(ParameterBag::class);
        $request->headers = $header;
        $request->attributes = $attr;

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($listing)
        ;

        $header
            ->method('get')
            ->with('content-type')
            ->willReturn($contentType)
        ;

        $this->bulkUploadService
            ->method('createUsersListCsvFile')
            ->with($listing)
            ->willReturn($this->filename)
        ;

        $this->pdfFileService
            ->method('createEndUserListPdfFile')
            ->with($listing)
            ->willReturn($this->filename)
        ;

        $response = $this->controller->__invoke($request);
        self::assertInstanceOf($responseClass , $response);
    }

    public static function dataInvokeDataProvider(): iterable
    {
        yield ['json', Listing::class];
        yield ['', Listing::class];
        yield [null, Listing::class];
        yield ['application/csv', BinaryFileResponse::class];
        yield ['text/csv', BinaryFileResponse::class];
        yield ['application/pdf', BinaryFileResponse::class];
    }

    public function testInvokeException(): void
    {
        $request = $this->createMock(Request::class);
        $listing = $this->createMock(Listing::class);

        $query = new InputBag();
        $query->set('format', null);
        $request->query = $query;

        $header = $this->createMock(HeaderBag::class);
        $attr = $this->createMock(ParameterBag::class);
        $request->headers = $header;
        $request->attributes = $attr;

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($listing)
        ;

        $header
            ->method('get')
            ->with('content-type')
            ->willReturn('application/image')
        ;

        $this->expectException(BadRequestHttpException::class);

        $this->controller->__invoke($request);
    }
}
