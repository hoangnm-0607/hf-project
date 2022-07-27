<?php

namespace Tests\DataProvider;

use App\DataProvider\PartnerProfilePublicItemDataProvider;
use App\Entity\PartnerProfile;
use App\Repository\PartnerProfileRepository;
use PHPUnit\Framework\TestCase;

class PartnerProfilePublicItemDataProviderTest extends TestCase
{
    private PartnerProfileRepository $profileRepository;
    private PartnerProfilePublicItemDataProvider $dataProvider;

    protected function setUp(): void
    {
        $this->profileRepository = $this->createMock(PartnerProfileRepository::class);
        $this->dataProvider = new PartnerProfilePublicItemDataProvider($this->profileRepository);
    }

    public function testSupports()
    {
        $isSupports = $this->dataProvider->supports(PartnerProfile::class, 'get_details');
        self::assertTrue($isSupports);
    }

    public function testGetItem()
    {
        $partnerProfile = new PartnerProfile();
        $partnerProfile->setCASPublicID('42');
        $this->profileRepository->method('getOnePartnerProfileByCasPublicId')->willReturn($partnerProfile);

        $result = $this->dataProvider->getItem(PartnerProfile::class, $partnerProfile->getCASPublicID());

        self::assertEquals(PartnerProfile::class, get_class($result));
    }
}
