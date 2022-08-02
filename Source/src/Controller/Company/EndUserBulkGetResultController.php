<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Service\EndUser\EndUserBulkUploadService;
use App\Traits\AuthorizationAssertHelperTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndUserBulkGetResultController
{
    use AuthorizationAssertHelperTrait;

    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(EndUserBulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function __invoke(int $companyId, string $confirmationId): JsonResponse
    {
        try {
            $file = $this->bulkUploadService->findFile($companyId, $confirmationId.'-result');
        } catch (NotFoundHttpException) {
            return new JsonResponse(['file-not-found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->authorizationAssertHelper->assertUserIsFileOwner($file);

        $content = json_decode($file->getData(), true);

        $file->delete();

        return new JsonResponse($content, JsonResponse::HTTP_OK);
    }
}
