<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\EndUser\EndUserBulkUploadService;
use App\Traits\AuthorizationAssertHelperTrait;
use Symfony\Component\HttpFoundation\JsonResponse;

class EndUserBulkGetFileController
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

        $content = json_decode($asset->getData(), true);

        return new JsonResponse($content, JsonResponse::HTTP_OK);
    }
}
