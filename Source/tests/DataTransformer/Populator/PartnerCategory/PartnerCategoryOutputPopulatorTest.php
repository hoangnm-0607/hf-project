<?php

namespace Tests\DataTransformer\Populator\PartnerCategory;

use App\DataProvider\Helper\AssetHelper;
use App\DataTransformer\Populator\PartnerCategory\PartnerCategoryOutputPopulator;
use App\Dto\PartnerCategoryDto;
use App\Entity\PartnerCategory;
use App\Service\I18NService;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset\Image;

class PartnerCategoryOutputPopulatorTest extends TestCase
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
                                  'https://hansefit.local/icons/partnerCatIconContour.jpg',
                                  'https://hansefit.local/icons/partnerCatIconFilled.jpg',
                                  'https://hansefit.local/images/partnerCatImage1.jpg'
                              );

    }

    public function testPopulateDE(): void
    {
        $populator = new PartnerCategoryOutputPopulator($this->assetHelperMock, $this->i18NServiceMock);
        $target    = new PartnerCategoryDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('de');

        $output = $populator->populate($this->createInputDE(), $target);

        self::assertEquals($this->createExpectedOutputDE(), $output);
    }

    public function testPopulateEN(): void
    {
        $populator = new PartnerCategoryOutputPopulator($this->assetHelperMock, $this->i18NServiceMock);
        $target    = new PartnerCategoryDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('en');

        $input = $this->createInputDE();
        $input->setName('Schwimmbad (EN)', 'en');
        $input->setDescription('Schwimmhalle mit drei Becken (EN)', 'en');
        $output = $populator->populate($input, $target);

        $partnerCategoryDto = $this->createExpectedOutputDE();
        $partnerCategoryDto->shortDescription = 'Schwimmbad (EN)';
        $partnerCategoryDto->longDescription = 'Schwimmhalle mit drei Becken (EN)';

        self::assertEquals($partnerCategoryDto, $output);
    }

    private function createInputDE(): PartnerCategory
    {
        $input = new PartnerCategory();
        $input->setId(100);
        $input->setName('Schwimmbad', 'de');
        $input->setDescription("Schwimmhalle mit drei Becken", 'de');
        $input->setCategoryIconContour($this->iconAssetMock);
        $input->setCategoryIconFilled($this->iconAssetMock);
        $input->setCategoryImage($this->imageAssetMock);

        return $input;

    }

    #[Pure] private function createExpectedOutputDE(): PartnerCategoryDto
    {
        $output = new PartnerCategoryDto();
        $output->id = 100;
        $output->iconUrlFilled = 'https://hansefit.local/icons/partnerCatIconFilled.jpg';
        $output->iconUrlContour = 'https://hansefit.local/icons/partnerCatIconContour.jpg';
        $output->imageUrl = 'https://hansefit.local/images/partnerCatImage1.jpg';
        $output->shortDescription = 'Schwimmbad';
        $output->longDescription = "Schwimmhalle mit drei Becken";

        return $output;

    }
}
