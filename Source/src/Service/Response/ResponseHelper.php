<?php

declare(strict_types=1);

namespace App\Service\Response;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ResponseHelper
{
    public static function getBinaryFileResponse(string $file, string $contentType, string $responseFileName): BinaryFileResponse
    {
        $response = new BinaryFileResponse($file, Response::HTTP_OK, ['Content-Type' => $contentType]);

        $response->deleteFileAfterSend(true);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $responseFileName,
        );

        return $response;
    }
}
