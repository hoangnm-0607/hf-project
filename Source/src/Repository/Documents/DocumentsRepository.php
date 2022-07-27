<?php

declare(strict_types=1);

namespace App\Repository\Documents;

use App\Service\FolderService;
use Pimcore\Model\Document\Printpage;

class DocumentsRepository
{
    public function findActivationTemplate(int $companyId, string $language, string $letter = FolderService::RESEND_LETTER): ?Printpage
    {
        $document = Printpage::getByPath(sprintf(FolderService::$companyActivationLetter, $companyId, $language, $letter));

        if (null === $document) {
            $document = Printpage::getByPath(sprintf(FolderService::$activationLetter, $language, $letter));
        }

        return $document;
    }
}
