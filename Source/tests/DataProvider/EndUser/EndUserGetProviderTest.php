<?php

declare(strict_types=1);

namespace Tests\DataProvider\EndUser;

use App\DataProvider\EndUser\EndUserGetProvider;
use App\DataProvider\EndUser\EndUserProvider;
use App\Entity\EndUser;
use App\Repository\EndUser\EndUserRepository;
use App\Security\AuthorizationAssertHelper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company as PimcoreCompany;
use Pimcore\Model\DataObject\EndUser as PimcoreEndUser;

final class EndUserGetProviderTest extends TestCase
{
    private EndUserGetProvider $provider;

    /** @var EndUserRepository|MockObject */
    private EndUserRepository|MockObject $repository;

    /** @var AuthorizationAssertHelper|MockObject  */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EndUserRepository::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->provider = new EndUserGetProvider($this->repository);
        $this->provider->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->repository,
            $this->provider,
            $this->authorizationAssertHelper,
        );
    }

    public function testGetItem(): void
    {
        $userId = 12345;
        $companyId = 616;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyManagerOrAdmin')
            ->with($companyId)
        ;

        $pimCoreUser = $this->createMock(PimcoreEndUser::class);
        $pimCoreCompany = $this->createMock(PimcoreCompany::class);

        $this->repository
            ->expects(self::once())
            ->method('findOneById')
            ->with($userId)
            ->willReturn($pimCoreUser)
        ;

        $pimCoreUser
            ->expects(self::once())
            ->method('getCompany')
            ->willReturn($pimCoreCompany)
        ;

        $pimCoreCompany
            ->expects(self::once())
            ->method('getId')
            ->willReturn($companyId)
        ;

        $result = $this->provider->getItem(EndUser::class, $userId, 'get');
        self::assertSame($pimCoreUser, $result);
        self::assertInstanceOf(PimcoreEndUser::class, $result);
    }

    public function testGetItemUserNotFound(): void
    {
        $userId = 12345;

        $this->authorizationAssertHelper
            ->expects(self::never())
            ->method('assertUserIsCompanyAdmin')
        ;

        $this->repository
            ->expects(self::once())
            ->method('findOneById')
            ->with($userId)
            ->willReturn(null)
        ;

        $result = $this->provider->getItem(EndUser::class, $userId, 'get');
        self::assertNull($result);
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
        yield [EndUser::class, 'get.as_admin', false];
        yield [EndUser::class, 'get.as_manager', true];
        yield [\stdClass::class, '', false];
    }
}
