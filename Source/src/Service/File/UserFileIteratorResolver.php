<?php

declare(strict_types=1);

namespace App\Service\File;

use Symfony\Component\HttpFoundation\File\File as UploadedFile;

class UserFileIteratorResolver
{
    /** @var iterable|FileIteratorFactoryInterface[] */
    private iterable $fileIteratorFactories;

    public function __construct(iterable $fileIteratorFactories)
    {
        $this->fileIteratorFactories = $fileIteratorFactories;
    }

    public function getFileIterator(UploadedFile $file): \Iterator
    {
        foreach ($this->fileIteratorFactories as $fileIteratorFactory) {
            if ($fileIteratorFactory->isSupport($file)) {
                return $fileIteratorFactory->createFileIterator($file);
            }
        }

        throw new \LogicException('Unsupported file!');
    }
}
