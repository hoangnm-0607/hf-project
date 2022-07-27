<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Message\EndUserBulkCreateMessage;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\InMemoryUserReaderService;
use App\Traits\MessageDispatcherTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Workflow\Exception\LogicException;

class EndUserBulkCreateController
{
    use MessageDispatcherTrait;

    private InMemoryUserReaderService $inMemoryUserReaderService;
    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(InMemoryUserReaderService $inMemoryUserReaderService, EndUserBulkUploadService $bulkUploadService)
    {
        $this->inMemoryUserReaderService = $inMemoryUserReaderService;
        $this->bulkUploadService = $bulkUploadService;
    }

    public function __invoke(int $companyId, string $confirmationId): JsonResponse
    {
        if ($this->bulkUploadService->isFileContainErrors($companyId, $confirmationId)) {
            throw new LogicException(sprintf('File %s/%s.json contains validation errors!', $companyId, $confirmationId));
        }

        $email = $this->inMemoryUserReaderService->getExtraField('email');

        $this->messageBus->dispatch(new EndUserBulkCreateMessage($companyId, $confirmationId, $email));

        return new JsonResponse();
    }
}
