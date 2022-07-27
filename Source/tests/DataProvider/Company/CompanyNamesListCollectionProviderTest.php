<?php

declare(strict_types=1);

namespace Tests\DataProvider\Company;

use App\DataProvider\Company\CompanyNamesListCollectionProvider;
use App\Entity\Company;
use App\Service\Company\CompanyService;
use App\Service\InMemoryUserReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company\Listing;

final class CompanyNamesListCollectionProviderTest extends TestCase
{
    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var InMemoryUserReaderService|MockObject */
    private InMemoryUserReaderService|MockObject $inMemoryUserReaderService;

    private CompanyNamesListCollectionProvider $provider;

    protected function setUp(): void
    {
        $this->companyService = $this->createMock(CompanyService::class);
        $this->inMemoryUserReaderService = $this->createMock(InMemoryUserReaderService::class);

        $this->provider = new CompanyNamesListCollectionProvider($this->companyService, $this->inMemoryUserReaderService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->provider,
            $this->inMemoryUserReaderService,
            $this->companyService
        );
    }

    /**
     * @param mixed  $resourceClass
     * @param string $to
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation(string $resourceClass, string $to, bool $supportResult): void
    {
        $result = $this->provider->supports($resourceClass, $to, []);
        self::assertSame($supportResult, $result);
    }

    public function dataProviderSupportTransformation(): iterable
    {
        yield [Company::class, 'get-current-user-company-list.as_manager', true];
        yield [Company::class, 'get', false];
        yield [\stdClass::class, 'get-current-user-company-list', false];
    }

    /**
     * @dataProvider collectionDataProvider
     */
    public function testGetCollection($decodedToken, array $ids, iterable $result): void
    {
        $this->inMemoryUserReaderService
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($decodedToken)
        ;

        $this->companyService
            ->method('findAllByIds')
            ->with($ids)
            ->willReturn($result)
        ;

        $response = $this->provider->getCollection(Company::class, 'get-current-user-company-list');
        self::assertSame($result, $response);
    }

    public function collectionDataProvider(): iterable
    {
        yield[ [['companyId' => 777]], [777], $this->createMock(Listing::class)];
        yield[ [['partnerId' => 111]], [], []];
        yield[ null, [], []];
    }
}
