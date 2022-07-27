<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\File\PdfFileService;
use App\Service\Response\ResponseHelper;
use Pimcore\Model\DataObject\EndUser\Listing;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CompanyEndUserListController
{
    private EndUserBulkUploadService $bulkUploadService;
    private PdfFileService $pdfFileService;

    public function __construct(EndUserBulkUploadService $bulkUploadService, PdfFileService $pdfFileService)
    {
        $this->bulkUploadService = $bulkUploadService;
        $this->pdfFileService = $pdfFileService;
    }

    public function __invoke(Request $request): Listing|Response
    {
        $format = $request->headers->get('content-type', 'json');

        $format = empty($format) ? 'json' : $format;

        $listing = $request->attributes->get('data');

        if (\in_array($format, ['text/csv', 'application/csv', 'csv'])) {
            $filename = $this->bulkUploadService->createUsersListCsvFile($listing);

            return ResponseHelper::getBinaryFileResponse($filename, 'application/csv', 'end-user-list.csv');
        }

        if (\in_array($format, ['application/pdf', 'pdf'])) {
            $pdf = $this->pdfFileService->createEndUserListPdfFile($listing);

            return ResponseHelper::getBinaryFileResponse($pdf, 'application/pdf', 'end-user-list.pdf');
        }

        if (\in_array($format, ['json', 'application/json'])) {
            return $listing;
        }

        throw new BadRequestHttpException('Unsupported format/Content-type. Use one from [json, csv, pdf]');
    }
}
