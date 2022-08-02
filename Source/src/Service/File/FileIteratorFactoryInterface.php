<?php

declare(strict_types=1);

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\File as UploadedFile;

interface FileIteratorFactoryInterface
{
    public function isSupport(UploadedFile $file): bool;

    public function createFileIterator(UploadedFile $file): \Iterator;
}
