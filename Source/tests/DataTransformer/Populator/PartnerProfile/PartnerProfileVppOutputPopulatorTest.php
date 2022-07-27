<?php

namespace Tests\DataTransformer\Populator\PartnerProfile;

use App\DataTransformer\Populator\PartnerProfile\PartnerProfileVppOutputPopulator;
use App\Dto\PartnerProfileVppOutputDto;
use App\Entity\PartnerProfile;
use App\Service\PartnerProfileService;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\ServicePackage;

class PartnerProfileVppOutputPopulatorTest extends TestCase
{
    public function testPopulate()
    {
        $target = new PartnerProfileVppOutputDto();

        $partnerProfileService = $this->createMock(PartnerProfileService::class);
        $partnerProfileService->method('getProfileCompletionPercentage')->willReturn(50);

        $populator = new PartnerProfileVppOutputPopulator($partnerProfileService);
        $output    = $populator->populate($this->createInput(), $target);
        $expectedOutput = $this->createExpectedOutput();

        self::assertEquals($expectedOutput->casPublicId, $output->casPublicId);
        self::assertEquals($expectedOutput->description, $output->description);
        self::assertEquals($expectedOutput->notes, $output->notes);
        self::assertEquals($expectedOutput->holidays, $output->holidays);
    }

    public function createInput(): PartnerProfile
    {
        $input = new PartnerProfile();

        $input->setCASPublicID('12345');
        $input->setShortDescription('Bester Club!', 'de');
        $input->setShortDescription('Best club!', 'en');
        $input->setNotesInformations('Sinnvolle Notizen', 'de');
        $input->setNotesInformations('Some nice notes', 'en');

        $input->setHolidays('Sonntags geöffnet', 'de');
        $input->setHolidays('Sundays open', 'en');

        $togetherProgram = new ServicePackage();
        $togetherProgram->setName(\App\Entity\ServicePackage::TWOGETHER_SERVICE_PACKAGE);
        $input->setServicePackages([$togetherProgram]);

        return $input;
    }
    #[Pure] public function createExpectedOutput(): PartnerProfileVppOutputDto
    {
        $output = new PartnerProfileVppOutputDto();

        $output->casPublicId = '12345';

        $output->completionPercentage = 50;

        $output->description['de'] = 'Bester Club!';
        $output->description['en'] = 'Best club!';

        $output->notes['de'] = 'Sinnvolle Notizen';
        $output->notes['en'] = 'Some nice notes';

        $output->holidays['de'] = 'Sonntags geöffnet';
        $output->holidays['en'] = 'Sundays open';

        $output->twogether = false;

        return $output;
    }
}
