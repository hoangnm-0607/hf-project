<?php

namespace Tests\DataProvider;

use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\DataProvider\PartnerProfileItemDataProvider;
use App\Entity\PartnerProfile;
use App\Repository\PartnerProfileRepository;
use App\Security\Validator\InMemoryUserValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PartnerProfileItemDataProviderTest extends TestCase
{
    protected MockObject|PartnerProfileRepository $profileRepositoryMock;
    protected PartnerProfileItemDataProvider $partnerProfileItemDataProvider;

    protected function setUp(): void
    {
        $this->profileRepositoryMock          = $this->createMock(PartnerProfileRepository::class);
        $inMemoryUserValidatorMock           = $this->createMock(InMemoryUserValidator::class);
        $this->partnerProfileItemDataProvider = new PartnerProfileItemDataProvider($this->profileRepositoryMock, $inMemoryUserValidatorMock);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testItemIsReturnedIfFound(): void
    {
        $partnerProfile = new PartnerProfile();

        $this->profileRepositoryMock->method('getOnePartnerProfileByCasPublicId')->willReturn($partnerProfile);

        $result = $this->partnerProfileItemDataProvider->getItem(PartnerProfile::class, 0);

        self::assertSame($partnerProfile, $result);
    }

    /**
     * @throws ResourceClassNotSupportedException
     */
    public function testNullIsReturnedIfNotFound(): void
    {
        $result = $this->partnerProfileItemDataProvider->getItem(PartnerProfile::class, 0);

        self::assertNull($result);
    }
}
