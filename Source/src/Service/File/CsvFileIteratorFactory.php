<?php

declare(strict_types=1);

namespace App\Service\File;

use App\Helper\FileHelper;
use Symfony\Component\HttpFoundation\File\File as UploadedFile;

class CsvFileIteratorFactory implements FileIteratorFactoryInterface
{
    public function isSupport(UploadedFile $file): bool
    {
        return FileHelper::isCsvFile($file);
    }

    public function createFileIterator(UploadedFile $file): \Iterator
    {
        return new UserCsvIterator($file->getPathname());
    }
}
