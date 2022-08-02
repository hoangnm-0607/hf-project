<?php

declare(strict_types=1);

namespace Tests\Service\File;

use App\Service\File\CsvFileIteratorFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File as UploadFile;

final class CsvFileIteratorFactoryTest extends TestCase
{
    private CsvFileIteratorFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new CsvFileIteratorFactory();
    }

    protected function tearDown(): void
    {
        unset($this->factory);
    }

    public function testSupport(): void
    {
        $file = $this->createMock(UploadFile::class);
        $file
            ->expects(self::once())
            ->method('getMimeType')
            ->willReturn('application/csv')
        ;

        $result = $this->factory->isSupport($file);
        self::assertTrue($result);
    }

    public function testSupportFalse(): void
    {
        $file = $this->createMock(UploadFile::class);
        $file
            ->expects(self::once())
            ->method('getMimeType')
            ->willReturn('application/text')
        ;

        $result = $this->factory->isSupport($file);
        self::assertFalse($result);
    }
}
