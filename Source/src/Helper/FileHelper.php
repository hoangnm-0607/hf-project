<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\File as UploadedFile;

class FileHelper
{
    private const CSV_MIMY_TYPES = [
        'text/csv', //csv
        'text/plain', //csv
        'application/csv', //csv
    ];

    private const XLS_MIMY_TYPES = [
        'application/vnd.ms-excel', //xls
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //xlsx
    ];

    public static function getBulkUploadMimeTypeSupport(): array
    {
        return array_merge(self::CSV_MIMY_TYPES, self::XLS_MIMY_TYPES);
    }

    public static function isCsvFile(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), self::CSV_MIMY_TYPES, true);
    }

    public static function isXlsFile(UploadedFile $file): bool
    {
        return in_array($file->getMimeType(), self::XLS_MIMY_TYPES, true);
    }
}
