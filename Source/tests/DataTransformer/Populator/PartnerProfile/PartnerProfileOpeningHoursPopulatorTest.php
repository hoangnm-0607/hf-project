<?php

namespace Tests\DataTransformer\Populator\PartnerProfile;

use App\DataTransformer\Populator\PartnerProfile\PartnerProfileOpeningHoursPopulator;
use App\Dto\OpeningHoursDto;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Data\StructuredTable;

class PartnerProfileOpeningHoursPopulatorTest extends TestCase
{

    public function testPopulate(): void
    {
        $populator = new PartnerProfileOpeningHoursPopulator();
        $target    = new PartnerProfileDto();

        $output = $populator->populate($this->createInput(), $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }

    public function createInput(): PartnerProfile
    {
        $structuredTable = new StructuredTable();
        $tableData = [
            'monday' => [
                'open' => 1,
                'time_from1' => "08:00",
                'time_to1' => "16:00",
                'time_from2' => null,
                'time_to2' => null,
                'time_from3' => null,
                'time_to3' => null,
            ]
        ];
        $structuredTable->setData($tableData);

        $input = new PartnerProfile();
        $input->setOpeningTimes($structuredTable);

        return $input;

    }

    #[Pure] public function createExpectedOutput(): PartnerProfileDto
    {
        $partnerProfileDto = new PartnerProfileDto();
        $openingHoursDto = new OpeningHoursDto();
        $openingHoursDto->opened = true;
        $openingHoursDto->weekday = 'monday';
        $openingHoursDto->times[] = [
          'from' => "08:00",
          'to' => "16:00"
        ];
        $partnerProfileDto->openingHours = [$openingHoursDto];

        return $partnerProfileDto;

    }
}
