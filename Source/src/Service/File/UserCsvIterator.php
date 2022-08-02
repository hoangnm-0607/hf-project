<?php

declare(strict_types=1);

namespace App\Service\File;

class UserCsvIterator extends \SplFileObject
{
    public function __construct(string $pathToFile, string $delimiter = ',', string $fieldEnclosure = '"', string $escapeChar = '\\')
    {
        parent::__construct($pathToFile);

        $this->setFlags(\SplFileObject::READ_CSV | \SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $this->setCsvControl($delimiter, $fieldEnclosure, $escapeChar);
    }
}
