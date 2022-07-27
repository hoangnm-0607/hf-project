<?php

declare(strict_types=1);

namespace Tests\DataProvider\Company;

use App\DataProvider\Company\CompanyGetProvider;
use App\Entity\Company;
use App\Security\AuthorizationAssertHelper;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company as PimcoreCompany;

final class CompanyGetProviderTest extends TestCase
{
    /** @var CompanyService|MockObject  */
    private CompanyService|MockObject $companyService;

    /** @var AuthorizationAssertHelper|MockObject  */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private CompanyGetProvider $provider;

    protected function setUp(): void
    {
        $this->companyService = $this->createMock(CompanyService::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->provider = new CompanyGetProvider($this->companyService);
        $this->provider->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->companyService,
            $this->authorizationAssertHelper,
            $this->provider,
        );
    }

    public function testGetItem(): void
    {
        $id = 12345;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyManagerOrAdmin')
            ->with($id)
        ;

        $pimCoreCompany = $this->createMock(PimcoreCompany::class);

        $this->companyService
            ->expects(self::once())
            ->method('findOneOrThrowException')
            ->with($id)
            ->willReturn($pimCoreCompany)
        ;

        $result = $this->provider->getItem(Company::class, $id, 'get');
        self::assertSame($pimCoreCompany, $result);
        self::assertInstanceOf(PimcoreCompany::class, $result);
    }

    /**
     * @param string $class
     * @param string $operation
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportClass
     */
    public function testSupport(string $class, string $operation, bool $supportResult): void
    {
        $result = $this->provider->supports($class, $operation);
        self::assertSame($supportResult, $result);
    }

    public static function dataProviderSupportClass(): iterable
    {
        yield [Company::class, 'get.as_admin', false];
        yield [Company::class, 'get.as_manager', true];
        yield [\stdClass::class, 'get.as_admin', false];
    }
}
