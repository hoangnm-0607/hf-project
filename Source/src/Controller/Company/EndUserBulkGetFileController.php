<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\EndUser\EndUserBulkUploadService;
use Symfony\Component\HttpFoundation\JsonResponse;

class EndUserBulkGetFileController
{
    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(EndUserBulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function __invoke(int $companyId, string $confirmationId): JsonResponse
    {
        $file = $this->bulkUploadService->findFile($companyId, $confirmationId);
        $content = json_decode($file->getData(), true);

        return new JsonResponse($content, JsonResponse::HTTP_OK);
    }
}
