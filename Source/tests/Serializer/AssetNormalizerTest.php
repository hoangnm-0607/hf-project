<?php

declare(strict_types=1);

namespace Tests\Serializer;

use ApiPlatform\Core\Serializer\ItemNormalizer;
use App\Serializer\AssetNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset\Text;

final class AssetNormalizerTest extends TestCase
{
    /** @var ItemNormalizer|MockObject */
    private ItemNormalizer|MockObject $itemNormalizer;

    private AssetNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->itemNormalizer = $this->createMock(ItemNormalizer::class);
        $this->normalizer = new AssetNormalizer();
        $this->normalizer->setNormalizer($this->itemNormalizer);
    }

    protected function tearDown(): void
    {
        unset(
            $this->itemNormalizer,
            $this->normalizer,
        );
    }

    /**
     * @param        $data
     * @param string $format
     * @param array  $context
     * @param bool   $result
     *
     * @dataProvider supportDataProvider
     */
    public function testSupport($data, string $format, array $context, bool $result): void
    {
        $actualResult = $this->normalizer->supportsNormalization($data, $format, $context);
        self::assertSame($result, $actualResult);
    }

    public function supportDataProvider(): iterable
    {
        yield [null, 'json', [], false];
        yield [$this->createMock(Text::class), 'json', ['collection_operation_name' => 'get'], false];
        yield [$this->createMock(Text::class), 'json', [], false];
        yield [$this->createMock(Text::class), 'text', [], false];
        yield [$this->createMock(Text::class), 'json', ['collection_operation_name' => 'get-bulk-upload-list.as_manager'], true];
    }

    public function testNormalize(): void
    {
        $result = ['data'];

        $asset = $this->createMock(Text::class);

        $this->itemNormalizer
            ->expects(self::once())
            ->method('normalize')
            ->with($asset, 'json', [])
            ->willReturn($result)
        ;

        $actualResult = $this->normalizer->normalize($asset, 'json', []);
        self::assertSame($result, $actualResult);
    }
}
