<?php

declare(strict_types=1);

namespace Tests\DataProvider\Company;

use App\DataProvider\Company\CompanyEndUserBulkUploadListCollectionProvider;
use App\Entity\Company;
use App\Repository\Asset\AssetRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CompanyEndUserBulkUploadListCollectionProviderTest extends TestCase
{
    private AssetRepository|MockObject $assetRepository;

    private CompanyEndUserBulkUploadListCollectionProvider $provider;

    protected function setUp(): void
    {
        $this->assetRepository = $this->createMock(AssetRepository::class);
        $this->provider = new CompanyEndUserBulkUploadListCollectionProvider($this->assetRepository);
    }

    protected function tearDown(): void
    {
        unset(
            $this->assetRepository,
            $this->provider,
        );
    }

    /**
     * @param string $class
     * @param bool   $supportResult
     * @param string $operationName
     *
     * @dataProvider dataProviderSupportClass
     */
    public function testSupport(string $class, string $operationName, bool $supportResult): void
    {
        $result = $this->provider->supports($class, $operationName);
        self::assertSame($supportResult, $result);
    }

    public static function dataProviderSupportClass(): iterable
    {
        yield [Company::class, 'get-bulk-upload-list.as_manager', true];
        yield [Company::class, 'get', false];
        yield [\stdClass::class, 'get-company-end-users', false];
    }
}
