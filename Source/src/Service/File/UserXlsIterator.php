<?php

declare(strict_types=1);

namespace App\Service\File;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;

class UserXlsIterator implements \Iterator
{
    private array $current;
    private int $key = 0;
    private string $lastColumn = '';

    private RowIterator $rowIterator;

    public function __construct(string $pathToFile)
    {
        $worksheet = IOFactory::load($pathToFile)->getActiveSheet();
        $this->rowIterator = $worksheet->getRowIterator();
        $this->current = $this->getRowArray(true);
    }

    public function current(): array
    {
        return $this->current;
    }

    public function next(): void
    {
        ++$this->key;
        $this->rowIterator->next();

        $this->current = $this->getRowArray();
    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        foreach ($this->current as $value) {
            if (!empty(trim($value))) {
                return true;
            }
        }

        return false;
    }

    public function rewind(): void
    {
        $this->key = 0;
        $this->rowIterator->rewind();
    }

    private function getRowArray(bool $asHeader = false): array
    {
        $rowResult = [];

        $row = $this->rowIterator->current();
        $cellIterator = $row->getCellIterator();

        foreach ($cellIterator as $key => $cell) {
            if (!$asHeader && $key === $this->lastColumn) {
                break;
            }

            $value = $cell->getFormattedValue();

            if ($asHeader && empty($value)) {
                $this->lastColumn = $key;

                break;
            }

            $rowResult[] = $value;
        }

        return $rowResult;
    }
}
