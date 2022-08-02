<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\EndUser\EndUserBulkUploadService;
use App\Traits\AuthorizationAssertHelperTrait;
use Symfony\Component\HttpFoundation\JsonResponse;

class EndUserBulkCancelController
{
    use AuthorizationAssertHelperTrait;

    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(EndUserBulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function __invoke(int $companyId, string $confirmationId): JsonResponse
    {
        $asset = $this->bulkUploadService->findFile($companyId, $confirmationId);

        $this->authorizationAssertHelper->assertUserIsFileOwner($asset);

        $this->bulkUploadService->removeEndUserUploadedFile($companyId, $confirmationId);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
