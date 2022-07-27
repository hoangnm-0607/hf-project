<?php

declare(strict_types=1);

namespace Tests\Service\File;

use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\File\PdfFileService;
use Knp\Snappy\Pdf;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\EndUser\Listing;
use Twig\Environment;

final class PdfFileServiceTest extends TestCase
{
    /** @var Environment|MockObject */
    private Environment|MockObject $twig;

    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $bulkUploadService;

    /** @var Pdf|MockObject */
    private Pdf|MockObject $pdf;

    private PdfFileService $service;

    protected function setUp(): void
    {
        $this->bulkUploadService = $this->createMock(EndUserBulkUploadService::class);
        $this->twig = $this->createMock(Environment::class);
        $this->pdf = $this->createMock(Pdf::class);

        $this->service = new PdfFileService($this->twig, $this->pdf, $this->bulkUploadService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->twig,
            $this->service,
            $this->bulkUploadService,
            $this->pdf,
        );
    }

    public function testCreateUSerListPdf()
    {
        $listing = $this->createMock(Listing::class);

        $userTable = [
            0 => ['header'],
            1 => ['data'],
        ];

        $this->bulkUploadService
            ->expects(self::once())
            ->method('prepareEndUserTable')
            ->with($listing)
            ->willReturn($userTable)
        ;

        $this->twig
            ->expects(self::once())
            ->method('render')
            ->with('enduser/list.html.twig', ['user_list' => [['data']] , 'header' => ['header']])
            ->willReturn('<p>Hello</p>')
        ;

        $this->pdf
            ->expects(self::once())
            ->method('getOutputFromHtml')
            ->with('<p>Hello</p>')
        ;

        $this->pdf->temporaryFiles = ['/tmp/filename.pdf', '/tmp/filename.html'];

        $result = $this->service->createEndUserListPdfFile($listing);

        self::assertIsString($result);
        self::assertSame('/tmp/filename.pdf', $result);
    }
}
