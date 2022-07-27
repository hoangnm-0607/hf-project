<?php

declare(strict_types=1);

namespace App\Service\File;

use Knp\Snappy\Pdf;

class PdfFactory
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function create(): Pdf
    {
        return new Pdf($this->projectDir.'/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
    }
}
