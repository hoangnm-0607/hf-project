<?php

declare(strict_types=1);

namespace Tests\DataTransformer\EndUser;

use App\DataTransformer\EndUser\EndUserImageUploadDataTransformer;
use App\Dto\VPP\Assets\AssetUploadDto;
use App\Entity\EndUser;
use App\Service\FolderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ApiPlatform\Core\Validator\ValidatorInterface;

final class EndUserImageUploadDataTransformerTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    /** @var FolderService|MockObject */
    private FolderService|MockObject $folderService;

    private EndUserImageUploadDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->folderService = $this->createMock(FolderService::class);

        $this->transformer = new EndUserImageUploadDataTransformer($this->folderService);
        $this->transformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->transformer,
            $this->folderService,
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

    public static function dataProviderSupportTransformation(): iterable
    {
        yield [new AssetUploadDto(), EndUser::class, ['item_operation_name' => 'put_image.as_admin'], true];
        yield [new AssetUploadDto(), EndUser::class, ['item_operation_name' => 'get'], false];
        yield [new AssetUploadDto(), \stdClass::class, ['item_operation_name' => 'put_image'], false];
        yield [null, EndUser::class, ['item_operation_name' => 'put_image.as_admin', 'input' => ['class' => AssetUploadDto::class]], true];
    }
}
