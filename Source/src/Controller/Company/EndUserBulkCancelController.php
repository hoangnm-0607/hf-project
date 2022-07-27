<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\EndUser\EndUserBulkUploadService;
use Symfony\Component\HttpFoundation\JsonResponse;

class EndUserBulkCancelController
{
    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(EndUserBulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function __invoke(int $companyId, string $confirmationId): JsonResponse
    {
        $this->bulkUploadService->removeEndUserUploadedFile($companyId, $confirmationId);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
