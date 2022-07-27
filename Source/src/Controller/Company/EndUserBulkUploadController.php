<?php

declare(strict_types=1);

namespace App\Controller\Company;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\InMemoryUserReaderService;
use App\Traits\SymfonyValidatorTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolationInterface;

class EndUserBulkUploadController
{
    use SymfonyValidatorTrait;

    private EndUserBulkUploadService $bulkUploadService;
    private InMemoryUserReaderService $inMemoryUserReaderService;

    public function __construct(EndUserBulkUploadService $bulkUploadService, InMemoryUserReaderService $inMemoryUserReaderService)
    {
        $this->bulkUploadService = $bulkUploadService;
        $this->inMemoryUserReaderService = $inMemoryUserReaderService;
    }

    public function __invoke(Request $request, int $companyId): JsonResponse
    {
        $uploadFile = $request->files->get('file');

        $errors = $this->validator->validate(
            $uploadFile,
            [
                new NotNull(['message' => 'file_is_missed']),
                new File(['mimeTypes' => ['text/csv', 'text/plain', 'application/csv']]),
            ]
        );

        if (0 !== \count($errors)) {
            $message = '';
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $message .= ' '.$error->getMessage();
            }

            throw new ValidationException(trim($message));
        }

        $userId = $this->inMemoryUserReaderService->getExtraField('id');

        $data = $this->bulkUploadService->analyzeFile($uploadFile, $companyId, $userId);

        return new JsonResponse($data, $data->code);
    }
}
