<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use App\DataTransformer\EndUser\EndUserBulkUploadListTransformer;
use App\Dto\EndUser\EndUserBulkUploadFileDto;
use App\Service\EndUser\EndUserBulkUploadService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset;

final class EndUserBulkUploadListTransformerTest extends TestCase
{
    /** @var EndUserBulkUploadService|MockObject */
    private EndUserBulkUploadService|MockObject $uploadService;

    private EndUserBulkUploadListTransformer $transformer;

    protected function setUp(): void
    {
        $this->uploadService = $this->createMock(EndUserBulkUploadService::class);

        $this->transformer = new EndUserBulkUploadListTransformer($this->uploadService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
        );
    }

    /**
     * @param mixed  $dto
     * @param string $to
     * @param array  $context
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation($dto, string $to, array $context, bool $supportResult): void
    {
        $result = $this->transformer->supportsTransformation($dto, $to, $context);
        self::assertSame($supportResult, $result);
    }

    public function dataProviderSupportTransformation(): iterable
    {
        yield [$this->createMock(Asset::class), EndUserBulkUploadFileDto::class, ['collection_operation_name' => 'get-bulk-upload-list.as_manager'], true];
        yield [$this->createMock(Asset::class), EndUserBulkUploadFileDto::class, ['item_operation_name' => 'get'], false];
        yield [$this->createMock(Asset::class), \stdClass::class, ['collection_operation_name' => 'get-bulk-upload-list'], false];
    }

    public function testTransform(): void
    {
        $asset = $this->createMock(Asset::class);

        $asset
            ->expects(self::once())
            ->method('getData')
            ->willReturn('{"data":"data"}')
        ;

        $this->uploadService
            ->expects(self::once())
            ->method('updateBulkUploadDtoFromAsset')
            ->with(self::isInstanceOf(EndUserBulkUploadFileDto::class), $asset, ['data' => 'data'])
        ;

        $this->transformer->transform($asset, EndUserBulkUploadFileDto::class, []);
    }
}
