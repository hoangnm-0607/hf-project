<?php

namespace Tests\DataTransformer\Populator\CourseCategory;

use App\DataProvider\Helper\AssetHelper;
use App\DataTransformer\Populator\CourseCategory\CourseCategoryOutputPopulator;
use App\Dto\CourseCategoryDto;
use App\Entity\CourseCategory;
use App\Service\I18NService;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset\Image;

class CourseCategoryOutputPopulatorTest extends TestCase
{
    private MockObject|I18NService $i18NServiceMock;
    private MockObject|AssetHelper $assetHelperMock;
    private Image|MockObject $iconAssetMock;
    private Image|MockObject $imageAssetMock;

    public function setUp(): void
    {
        $this->assetHelperMock = $this->createMock(AssetHelper::class);
        $this->iconAssetMock = $this->createMock(Image::class);
        $this->imageAssetMock = $this->createMock(Image::class);
        $this->i18NServiceMock = $this->createMock(I18NService::class);

        $this->assetHelperMock->method('getAssetUrl')
                              ->withConsecutive(
                                  [$this->iconAssetMock],
                                  [$this->imageAssetMock]
                              )
                              ->willReturnOnConsecutiveCalls(
                                  'https://hansefit.local/icons/courseCatIconContour.jpg',
                                  'https://hansefit.local/icons/courseCatIconFilled.jpg',
                                  'https://hansefit.local/images/courseCatImage1.jpg'
                              );

    }

    public function testPopulateDE(): void
    {
        $populator = new CourseCategoryOutputPopulator($this->assetHelperMock, $this->i18NServiceMock);
        $target    = new CourseCategoryDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('de');

        $output = $populator->populate($this->createInputDE(), $target);

        self::assertEquals($this->createExpectedOutputDE(), $output);
    }

    public function testPopulateEN(): void
    {
        $populator = new CourseCategoryOutputPopulator($this->assetHelperMock, $this->i18NServiceMock);
        $target    = new CourseCategoryDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('en');

        $input = $this->createInputDE();
        $input->setName('Pilates (EN)', 'en');
        $input->setDescription("Pilates für Anfänger (EN)", 'en');

        $output = $populator->populate($input, $target);

        $courseCategoryDto = $this->createExpectedOutputDE();
        $courseCategoryDto->shortDescription = 'Pilates (EN)';
        $courseCategoryDto->longDescription = 'Pilates für Anfänger (EN)';

        self::assertEquals($courseCategoryDto, $output);
    }

    private function createInputDE(): CourseCategory
    {
        $input = new CourseCategory();
        $input->setId(100);
        $input->setName('Pilates', 'de');
        $input->setDescription("Pilates für Anfänger", 'de');
        $input->setCategoryIconContour($this->iconAssetMock);
        $input->setCategoryIconFilled($this->iconAssetMock);
        $input->setCategoryImage($this->imageAssetMock);

        return $input;
    }

    #[Pure] private function createExpectedOutputDE(): CourseCategoryDto
    {
        $output = new CourseCategoryDto();
        $output->id = 100;
        $output->iconUrlContour = 'https://hansefit.local/icons/courseCatIconContour.jpg';
        $output->iconUrlFilled = 'https://hansefit.local/icons/courseCatIconFilled.jpg';
        $output->imageUrl = 'https://hansefit.local/images/courseCatImage1.jpg';
        $output->shortDescription = 'Pilates';
        $output->longDescription = "Pilates für Anfänger";

        return $output;
    }
}
