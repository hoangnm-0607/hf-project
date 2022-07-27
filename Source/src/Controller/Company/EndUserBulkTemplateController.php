<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\Company\CompanyService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class EndUserBulkTemplateController
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function __invoke(int $companyId): Response
    {
        $filename = $this->companyService->createTemplateUsersFileForCompany($companyId);

        $response = new BinaryFileResponse($filename, Response::HTTP_OK, ['Content-Type' => 'application/csv']);

        $response->deleteFileAfterSend(true);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            sprintf('template-company-%s.csv', $companyId)
        );

        return $response;
    }
}
