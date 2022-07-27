<?php

namespace Tests\DataTransformer\Populator\PartnerProfile;

use App\DataTransformer\Populator\PartnerProfile\PartnerProfileBaseDataOutputPopulator;
use App\Dto\PartnerProfileDto;
use App\Entity\PartnerProfile;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Data\GeoCoordinates;
use Pimcore\Model\DataObject\ServicePackage;

class PartnerProfileBaseDataOutputPopulatorTest extends TestCase
{
    public function testPopulate()
    {
        $target = new PartnerProfileDto();

        $populator = new PartnerProfileBaseDataOutputPopulator();
        $output    = $populator->populate($this->createInput(), $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }

    public function createInput(): PartnerProfile
    {
        $input = new PartnerProfile();
        $input->setName('Test-Club');
        $input->setCASPublicID('12345');
        $input->setCountry('DE');
        $input->setStreet('Fitnesstraße');
        $input->setNumber('2000');
        $input->setZip('12345');
        $input->setCity('Fitort');
        $input->setGeoData(new GeoCoordinates('55.122315','8.456521'));
        $input->setEmail('xyz@notset.fake');
        $input->setTelephone('44455566');
        $input->setWebsite('https://notset.fake');
        $input->setHansefitCard('Ja');
        $input->setTags('Fit,Fitter');
        $input->setCheckInApp(true);
        $input->setCheckInCard(false);
        $input->setShowOpeningTimes('Ja');

        return $input;
    }
    #[Pure]
    public function createExpectedOutput(): PartnerProfileDto
    {
        $output = new PartnerProfileDto();
        $output->studioName = 'Test-Club';
        $output->publicId = '12345';
        $output->country = 'DE';
        $output->street = 'Fitnesstraße';
        $output->streetNumber = '2000';
        $output->zip = '12345';
        $output->city = 'Fitort';
        $output->coordLat = '55.122315';
        $output->coordLong = '8.456521';
        $output->email = 'xyz@notset.fake';
        $output->phonenumber = '44455566';
        $output->website = 'https://notset.fake';
        $output->hansefitCard = true;
        $output->checkInApp = true;
        $output->checkInCard = false;
        $output->showOpeningTimes = true;
        $output->tags = 'Fit,Fitter';

        return $output;
    }
}
