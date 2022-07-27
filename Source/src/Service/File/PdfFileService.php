<?php

declare(strict_types=1);

namespace App\Service\File;

use App\Entity\EndUser;
use App\Service\EndUser\EndUserBulkUploadService;
use Knp\Snappy\Pdf;
use Pimcore\Model\DataObject\EndUser\Listing;
use Pimcore\Model\Document;
use Pimcore\Web2Print\Processor;
use Twig\Environment;

class PdfFileService
{
    private Environment $twig;
    private Pdf $pdf;
    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(Environment $twig, Pdf $pdf, EndUserBulkUploadService $bulkUploadService)
    {
        $this->twig = $twig;
        $this->pdf = $pdf;
        $this->bulkUploadService = $bulkUploadService;
    }

    public function createEndUserListPdfFile(Listing $userList): ?string
    {
        $usersTable = $this->bulkUploadService->prepareEndUserTable($userList);

        $html = $this->twig->render('enduser/list.html.twig', [
            'user_list' => array_slice($usersTable, 1), 'header' => $usersTable[0]
        ]);

        $this->pdf->getOutputFromHtml($html);

        return $this->findPdfFileNameFromArray($this->pdf->temporaryFiles);
    }

    public function createEndUserActivationPdfFile(Document\Printpage $document, EndUser $endUser): string
    {
        $adapter = Processor::getInstance();

        $html = $document->renderDocument(['end-user' => $endUser]);

        return $adapter->getPdfFromString($html);
    }

    private function findPdfFileNameFromArray(array $files): ?string
    {
        $resultFile = null;

        foreach ($files as $filename) {
            if (str_ends_with($filename, '.pdf')) {
                $resultFile = $filename;

                break;
            }
        }

        return $resultFile;
    }
}
