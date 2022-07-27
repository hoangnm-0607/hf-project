<?php

declare(strict_types=1);

namespace Tests\Dto\Company;

use App\Dto\Company\CompanyAssetDocumentInputDto;
use PHPUnit\Framework\TestCase;

final class CompanyAssetDocumentInputDtoTest extends TestCase
{
    public function testConstructor(): void
    {
        $dto = new CompanyAssetDocumentInputDto();
        $dto->language = 'en';
        $dto->folderName = 'folder';
        $dto->filename = 'file';
        $dto->originalFilename = 'origin';

        self::assertSame('en', $dto->language);
        self::assertSame('folder', $dto->folderName);
        self::assertSame('file', $dto->filename);
        self::assertSame('origin', $dto->originalFilename);
    }
}
