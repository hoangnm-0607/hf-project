<?php

declare(strict_types=1);

namespace App\Service\File;

use App\Helper\FileHelper;
use Symfony\Component\HttpFoundation\File\File as UploadedFile;

class XlsFileIteratorFactory implements FileIteratorFactoryInterface
{
    public function isSupport(UploadedFile $file): bool
    {
        return FileHelper::isXlsFile($file);
    }

    public function createFileIterator(UploadedFile $file): \Iterator
    {
        return new UserXlsIterator($file->getPathname());
    }
}
