<?php

namespace Tests\DataTransformer\Populator\PartnerProfile;

use App\DataProvider\Helper\AssetHelper;
use App\DataTransformer\Populator\PartnerProfile\PartnerProfileAssetsOutputPopulator;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use App\Service\I18NService;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\Data\Video;
use Pimcore\Model\DataObject\Fieldcollection;

class PartnerProfileAssetsOutputPopulatorTest extends TestCase
{

    /**
     * @var AssetHelper|MockObject
     */
    protected MockObject|AssetHelper $assetHelperMock;
    /**
     * @var MockObject|Image
     */
    protected Image|MockObject $logoImageMock;
    /**
     * @var MockObject|Image
     */
    protected Image|MockObject $pictureImageMock1;
    /**
     * @var MockObject|Image
     */
    protected Image|MockObject $pictureImageMock2;
    /**
     * @var MockObject|Image
     */
    protected Image|MockObject $studioImageMock;
    protected PartnerProfileAssetsOutputPopulator $populator;
    protected MockObject|Video $studioVideoMock;
    protected MockObject|Video $videoAssetMock;
    private MockObject|I18NService $i18NServiceMock;

    protected function setUp(): void
    {
        $this->i18NServiceMock = $this->createMock(I18NService::class);

        $this->assetHelperMock = $this->createMock(AssetHelper::class);
        $this->logoImageMock = $this->createMock(Image::class);
        $this->studioImageMock = $this->createMock(Image::class);
        $this->pictureImageMock1 = $this->createMock(Image::class);
        $this->pictureImageMock2 = $this->createMock(Image::class);

        $this->videoAssetMock = $this->createMock(\Pimcore\Model\Asset\Video::class);

        $this->studioVideoMock = $this->createMock(\Pimcore\Model\Asset\Video::class);
        $this->studioVideoMock->method('getType')->willReturn('asset');
        $this->studioVideoMock->method('getData')->willReturn($this->videoAssetMock);

        $this->populator = new PartnerProfileAssetsOutputPopulator($this->assetHelperMock, $this->i18NServiceMock);
    }

    public function testPopulateWithYoutubeDE(): void
    {
        $target    = new PartnerProfileDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('de');

        $this->assetHelperMock->method('getAssetUrl')
            ->withConsecutive(
                [$this->logoImageMock],
                [$this->studioImageMock],
                [$this->pictureImageMock1],
                [$this->pictureImageMock2]
            )
            ->willReturnOnConsecutiveCalls(
                'https://hansefit.local/CourseIcons/StudioLogo.jpg',
                'https://hansefit.local/images/StudioImage.jpg',
                'https://hansefit.local/pictures/pic1.jpg',
                'https://hansefit.local/pictures/pic2.jpg'
            );


        $output = $this->populator->populate($this->createInputDE(), $target);

        self::assertEquals($this->createExpectedOutputDE(), $output);
    }

    public function testPopulateWithYoutubeEN(): void
    {
        $target    = new PartnerProfileDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('en');

        $this->assetHelperMock->method('getAssetUrl')
                              ->withConsecutive(
                                  [$this->logoImageMock],
                                  [$this->studioImageMock],
                                  [$this->pictureImageMock1],
                                  [$this->pictureImageMock2]
                              )
                              ->willReturnOnConsecutiveCalls(
                                  'https://hansefit.local/CourseIcons/StudioLogo.jpg',
                                  'https://hansefit.local/images/StudioImage.jpg',
                                  'https://hansefit.local/pictures/pic1.jpg',
                                  'https://hansefit.local/pictures/pic2.jpg'
                              );


        $output = $this->populator->populate($this->createInputEN(), $target);

        self::assertEquals($this->createExpectedOutputEN(), $output);
    }

    public function testPopulateWithVideoAsset(): void
    {
        $input = new PartnerProfile();
        $input->setStudioVideo($this->studioVideoMock);

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('de');

        $this->assetHelperMock->method('getAssetUrl')
            ->with($this->videoAssetMock)
            ->willReturn('https://hansefit.local/Big_Buck_Bunny_720_10s_30MB.mp4');


        $target = new PartnerProfileDto();
        $output = $this->populator->populate($input, $target);

        $expectedOutput = new PartnerProfileDto();
        $expectedOutput->video["url"] = 'https://hansefit.local/Big_Buck_Bunny_720_10s_30MB.mp4';
        $expectedOutput->video["title"] = null;
        $expectedOutput->video["type"] = 'asset';

        self::assertEquals($expectedOutput, $output);
    }

    private function createInputDE(): PartnerProfile
    {
        $input = new PartnerProfile();
        $input->setLogo($this->logoImageMock);
        $input->setStudioImage($this->studioImageMock);
        $input->setStudioImageTitle('Studio Titel 1', 'de');

        $galleryPic1 = new Fieldcollection\Data\ImageDescriptionBlock();
        $galleryPic1->setImage($this->pictureImageMock1)->setTitle('Titel 1', 'de');

        $galleryPic2 = new Fieldcollection\Data\ImageDescriptionBlock();
        $galleryPic2->setImage($this->pictureImageMock2)->setTitle('Titel 2', 'de');

        $gallery = new Fieldcollection();
        $gallery->setItems([$galleryPic1, $galleryPic2]);

        $studioVideo = new Video();
        $studioVideo->setType('youtube');
        $studioVideo->setData('dQw4w9WgXcQ');

        $input->setStudioVideo($studioVideo);
        $input->setStudioVideoTitle('Nggyu!', 'de');

        $input->setGallery($gallery);

        return $input;

    }

    private function createInputEN(): PartnerProfile
    {
        $input = $this->createInputDE();
        $input->setStudioImageTitle('Studio Titel 1 (EN)', 'en');

        $galleryPic1 = new Fieldcollection\Data\ImageDescriptionBlock();
        $galleryPic1->setImage($this->pictureImageMock1)->setTitle('Titel 1 (EN)', 'en');

        $galleryPic2 = new Fieldcollection\Data\ImageDescriptionBlock();
        $galleryPic2->setImage($this->pictureImageMock2)->setTitle('Titel 2 (EN)', 'en');

        $gallery = new Fieldcollection();
        $gallery->setItems([$galleryPic1, $galleryPic2]);

        $studioVideo = new Video();
        $studioVideo->setType('youtube');
        $studioVideo->setData('dQw4w9WgXcQ');

        $input->setStudioVideo($studioVideo);
        $input->setStudioVideoTitle('Nggyu (EN)!', 'en');

        $input->setGallery($gallery);

        return $input;
    }

    #[Pure] private function createExpectedOutputDE(): PartnerProfileDto
    {
        $output = new PartnerProfileDto();
        $output->logo = 'https://hansefit.local/CourseIcons/StudioLogo.jpg';
        $output->studioImage = [
            'url' => 'https://hansefit.local/images/StudioImage.jpg',
            'title' => 'Studio Titel 1'
        ];

        $output->pictures = [
            [
                'url' => 'https://hansefit.local/pictures/pic1.jpg',
                'title' => 'Titel 1'
            ],
            [
                'url' => 'https://hansefit.local/pictures/pic2.jpg',
                'title' => 'Titel 2'
            ]
        ];

        $output->video = [
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'title' => 'Nggyu!',
            'type' => 'youtube'
        ];

        return $output;
    }

    private function createExpectedOutputEN(): PartnerProfileDto
    {
        $output = $this->createExpectedOutputDE();
        $output->studioImage['title'] = 'Studio Titel 1 (EN)';

        $output->pictures[0]['title'] = 'Titel 1 (EN)';
        $output->pictures[1]['title'] = 'Titel 2 (EN)';

        $output->video['title'] = 'Nggyu (EN)!';

        return $output;
    }
}
