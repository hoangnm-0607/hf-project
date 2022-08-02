<?php

declare(strict_types=1);

namespace Tests\Service\File;

use App\Service\File\XlsFileIteratorFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File as UploadFile;

final class XlsFileIteratorFactoryTest extends TestCase
{
    private XlsFileIteratorFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new XlsFileIteratorFactory();
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
            ->willReturn('application/vnd.ms-excel')
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
