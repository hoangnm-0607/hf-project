<?php

namespace Tests\DataTransformer\Populator\Courses;

use App\DataProvider\Helper\AssetHelper;
use App\DataTransformer\Populator\Courses\CoursesOutputPopulator;
use App\Dto\CoursesDto;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Service\DataObjectService;
use App\Service\I18NService;
use App\Service\TranslatorService;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset\Image;

class CoursesOutputPopulatorTest extends TestCase
{
    protected MockObject|AssetHelper $assetHelperMock;
    protected DataObjectService|MockObject $dataObjectServiceMock;
    protected Image|MockObject $courseImageMock;
    protected MockObject|PartnerProfile $parentProfileMock;
    private MockObject|TranslatorService $translatorServiceMock;
    private MockObject|I18NService $i18NServiceMock;

    public function setUp():void
    {
        $this->assetHelperMock       = $this->createMock(AssetHelper::class);
        $this->dataObjectServiceMock = $this->createMock(DataObjectService::class);
        $this->courseImageMock       = $this->createMock(Image::class);
        $this->parentProfileMock = $this->createMock(PartnerProfile::class);
        $this->i18NServiceMock = $this->createMock(I18NService::class);
        $this->translatorServiceMock = $this->createMock(TranslatorService::class);

        $this->assetHelperMock
            ->method('getAssetUrl')
            ->with($this->courseImageMock)
            ->willReturn('https://hansefit.local/images/courseImage.jpg');

        $this->parentProfileMock
            ->method('getCASPublicID')
            ->willReturn(9090);

        $this->parentProfileMock
            ->method('getShortDescription')
            ->willReturn('Beschreibung DE');

        $this->parentProfileMock
            ->method('getName')
            ->willReturn('Yogi-Studios');


    }

    public function testPopulateWithPartnerIdDE(): void
    {
        $this->dataObjectServiceMock
            ->method('getRecursiveParent')
            ->willReturn($this->parentProfileMock);

        $populator = new CoursesOutputPopulator($this->assetHelperMock, $this->dataObjectServiceMock, $this->i18NServiceMock, $this->translatorServiceMock);
        $target    = new CoursesDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('de');

        $this->translatorServiceMock->method('getTranslatedValues')
                                    ->with(['Einsteiger','Profis'])
                                    ->willReturn(['Einsteiger','Profis']);

        $output = $populator->populate($this->createInputDE(), $target, '');

        self::assertEquals($this->createExpectedOutputDE(), $output);
    }

    public function testPopulateWithPartnerIdEN(): void
    {
        $this->dataObjectServiceMock
            ->method('getRecursiveParent')
            ->willReturn($this->parentProfileMock);

        $populator = new CoursesOutputPopulator($this->assetHelperMock, $this->dataObjectServiceMock, $this->i18NServiceMock, $this->translatorServiceMock);
        $target    = new CoursesDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('en');

        $this->translatorServiceMock->method('getTranslatedValues')
                                    ->with(['Einsteiger','Profis'])
                                    ->willReturn(['Beginner','Advanced']);

        $source = $this->createInputDE();
        $source->setCourseName('Yoga für Einsteiger (EN)', 'en');
        $source->setDescription('Unser Yoga-Kurs für Einsteiger. (EN)', 'en');
        $source->setNeededAccessoires('Yogamatte, Yogablock (EN)', 'en');
        $output = $populator->populate($source, $target, '');

        $coursesDto = $this->createExpectedOutputDE();
        $coursesDto->courseName = 'Yoga für Einsteiger (EN)';
        $coursesDto->shortDescription = 'Unser Yoga-Kurs für Einsteiger. (EN)';
        $coursesDto->neededAccessoires = 'Yogamatte, Yogablock (EN)';
        $coursesDto->level = 'Beginner,Advanced';

        self::assertEquals($coursesDto, $output);
    }

    public function testPopulateWithoutPartnerId():void
    {
        $this->dataObjectServiceMock
            ->method('getRecursiveParent')
            ->willReturn(null);

        $populator = new CoursesOutputPopulator($this->assetHelperMock, $this->dataObjectServiceMock, $this->i18NServiceMock, $this->translatorServiceMock);
        $target    = new CoursesDto();

        $input = new Courses();
        $input->setCourseImage($this->courseImageMock);

        $expectedOutput = new CoursesDto();

        $output = $populator->populate($input, $target, '');

        self::assertEquals($expectedOutput, $output);
    }

    private function createInputDE(): Courses
    {
        $input = new Courses();
        $input->setCourseID('444');
        $input->setCourseName('Yoga für Einsteiger', 'de');
        $input->setCourseType('OnlineCourse');
        $input->setDescription('Unser Yoga-Kurs für Einsteiger.', 'de');
        $input->setCourseImage($this->courseImageMock);
        $input->setLevel(['Einsteiger','Profis']);
        $input->setNeededAccessoires('Yogamatte, Yogablock', 'de');
        $input->setDuration(90);
        $input->setExclusiveCourse(true);
        $input->setCourseInstructor('Yogi B.');


        return $input;

    }

    #[Pure]
    private function createExpectedOutputDE(): CoursesDto
    {
        $output = new CoursesDto();
        $output->courseId = '444';
        $output->courseName = 'Yoga für Einsteiger';
        $output->courseType = 'OnlineCourse';
        $output->shortDescription = 'Unser Yoga-Kurs für Einsteiger.';
        $output->courseImage = 'https://hansefit.local/images/courseImage.jpg';
        $output->level = 'Einsteiger,Profis';
        $output->neededAccessoires = 'Yogamatte, Yogablock';
        $output->courseDuration = 90;
        $output->exclusiveCourse = true;
        $output->courseInstructor = 'Yogi B.';
        $output->partnerDescription = 'Beschreibung DE';
        $output->partnerName = 'Yogi-Studios';
        $output->packageId = 30;

        $output->partnerId = 9090;
        $output->published = false;

        return $output;
    }
}
