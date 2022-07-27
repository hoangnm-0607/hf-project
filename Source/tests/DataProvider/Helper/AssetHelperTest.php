<?php

namespace Tests\DataProvider\Helper;

use App\DataProvider\Helper\AssetHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset;

class AssetHelperTest extends TestCase
{
    private AssetHelper $assetHelper;
    private MockObject|Asset\Image\Thumbnail $imageThumbnailMock;
    private MockObject|Asset\Video $assetVideoMock;
    private Asset\Image|MockObject $assetImageMock;

    protected function setUp(): void
    {
        $this->assetImageMock     = $this->createMock(Asset\Image::class);
        $this->assetVideoMock     = $this->createMock(Asset\Video::class);
        $this->imageThumbnailMock = $this->createMock(Asset\Image\Thumbnail::class);

        $this->assetHelper = new AssetHelper();

    }

    public function testGetAssetUrlReturnsImageUrl()
    {
        $this->assetImageMock->method('getThumbnail')
                             ->with('imageThumbnail')
                             ->willReturn($this->imageThumbnailMock);

        $this->imageThumbnailMock->method('getPathReference')
            ->willReturn(['src' => '/link/zum/bild.jpg']);

        $output      = $this->assetHelper->getAssetUrl($this->assetImageMock, 'imageThumbnail');

        self::assertEquals('/link/zum/bild.jpg', $output);
    }

    public function testGetAssetUrlReturnsVideoUrl()
    {
        $this->assetVideoMock->method('getThumbnail')->willReturn(null);
        $this->assetVideoMock->method('getFullPath')->willReturn('/link/zum/video.mp4');

        $output      = $this->assetHelper->getAssetUrl($this->assetVideoMock);

        self::assertEquals('/link/zum/video.mp4', $output);
    }

    public function testGetAssetReturnsNullWithEmptyAsset()
    {
        self::assertNull($this->assetHelper->getAssetUrl(null));
    }
}
