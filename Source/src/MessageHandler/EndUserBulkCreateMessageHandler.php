<?php

namespace App\MessageHandler;

use App\Message\EndUserBulkCreateMessage;
use App\Service\EndUser\EndUserBulkUploadService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

#[AsMessageHandler]
class EndUserBulkCreateMessageHandler implements MessageHandlerInterface
{
    private EndUserBulkUploadService $bulkUploadService;

    public function __construct(EndUserBulkUploadService $bulkUploadService)
    {
        $this->bulkUploadService = $bulkUploadService;
    }

    public function __invoke(EndUserBulkCreateMessage $message)
    {
        $this->bulkUploadService->createUsersForCompanyFromFile($message);
    }
}
