<?php

namespace Tests\DataTransformer\Populator\PartnerProfile;

use App\DataTransformer\Populator\PartnerProfile\PartnerProfileOutputPopulator;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use App\Service\I18NService;
use App\Service\TranslatorService;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PartnerProfileOutputPopulatorTest extends TestCase
{
    private MockObject|I18NService $i18NServiceMock;

    protected function setUp(): void
    {
        $this->translatorServiceMock = $this->createMock(TranslatorService::class);
        $this->i18NServiceMock = $this->createMock(I18NService::class);
    }

    public function testPopulateDE()
    {
        $target = new PartnerProfileDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('de');


        $populator = new PartnerProfileOutputPopulator($this->i18NServiceMock);
        $output    = $populator->populate($this->createInput(), $target);

        self::assertEquals($this->createExpectedOutputDE(), $output);
    }

    public function testPopulateEN()
    {
        $target = new PartnerProfileDto();

        $this->i18NServiceMock->method('getLanguageFromRequest')
                              ->willReturn('en');

        $source    = $this->createInput();
        $source->setShortDescription('Best club!', 'en');
        $source->setNotesInformations('Some nice notes', 'en');
        $source->setHolidays('Sundays open', 'en');

        $populator = new PartnerProfileOutputPopulator($this->i18NServiceMock);
        $output    = $populator->populate($source, $target);

        self::assertEquals($this->createExpectedOutputEN(), $output);
    }

    public function createInput(): PartnerProfile
    {
        $input = new PartnerProfile();
        $input->setShortDescription('Bester Club!', 'de');
        $input->setNotesInformations('Sinnvolle Notizen', 'de');
        $input->setHolidays('Sonntags geöffnet', 'de');

        $input->setStudioVisibility('Ja');


        return $input;
    }
    #[Pure] public function createExpectedOutputDE(): PartnerProfileDto
    {
        $output = new PartnerProfileDto();
        $output->description = 'Bester Club!';
        $output->notes = 'Sinnvolle Notizen';
        $output->holidays = 'Sonntags geöffnet';
        $output->studioVisibility = true;
        $output->published = false;

        return $output;
    }
    #[Pure] public function createExpectedOutputEN(): PartnerProfileDto
    {
        $output = new PartnerProfileDto();
        $output->description = 'Best club!';
        $output->notes = 'Some nice notes';
        $output->holidays = 'Sundays open';
        $output->studioVisibility = true;
        $output->published = false;

        return $output;
    }
}
