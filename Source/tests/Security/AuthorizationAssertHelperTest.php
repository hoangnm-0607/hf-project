<?php

declare(strict_types=1);

namespace Tests\Security;

use App\Exception\Http\Authorization\AccessDeniedException;
use App\Security\AuthorizationAssertHelper;
use App\Security\Voter\CompanyVoter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AuthorizationAssertHelperTest extends TestCase
{
    private AuthorizationCheckerInterface|MockObject $authorizationChecker;

    private AuthorizationAssertHelper $assertHelper;

    protected function setUp(): void
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $this->assertHelper = new AuthorizationAssertHelper();
        $this->assertHelper->setAuthorizationChecker($this->authorizationChecker);
    }

    protected function tearDown(): void
    {
        unset(
            $this->assertHelper,
            $this->authorizationChecker,
        );
    }

    public function testUserIsCompanyAdmin(): void
    {
        $this->authorizationChecker
            ->expects(self::once())
            ->method('isGranted')
            ->with(CompanyVoter::ADMIN, '12345')
            ->willReturn(true)
        ;

        $this->assertHelper->assertUserIsCompanyAdmin('12345');
    }

    public function testUserIsCompanyAdminException(): void
    {
        $this->authorizationChecker
            ->expects(self::once())
            ->method('isGranted')
            ->with(CompanyVoter::ADMIN, '12345')
            ->willReturn(false)
        ;

        $this->expectException(AccessDeniedException::class);

        $this->assertHelper->assertUserIsCompanyAdmin('12345');
    }

    public function testUserIsCompanyAnyRole(): void
    {
        $this->authorizationChecker
            ->expects(self::once())
            ->method('isGranted')
            ->with(CompanyVoter::ANY_ROLE, '12345')
            ->willReturn(true)
        ;

        $this->assertHelper->assertUserIsCompanyAnyRole('12345');
    }

    public function testUserIsCompanyAnyRoleException(): void
    {
        $this->authorizationChecker
            ->expects(self::once())
            ->method('isGranted')
            ->with(CompanyVoter::ANY_ROLE, '12345')
            ->willReturn(false)
        ;

        $this->expectException(AccessDeniedException::class);

        $this->assertHelper->assertUserIsCompanyAnyRole('12345');
    }

    public function testUserIsCompanyAdminOrManagerException(): void
    {
        $this->authorizationChecker
            ->expects(self::atLeast(2))
            ->method('isGranted')
            ->withConsecutive([CompanyVoter::ADMIN, '12345'], [CompanyVoter::MANAGER, '12345'])
            ->willReturnOnConsecutiveCalls(false, false)
        ;

        $this->expectException(AccessDeniedException::class);

        $this->assertHelper->assertUserIsCompanyManagerOrAdmin('12345');
    }

    public function testUserIsCompanyAdminOrManagerExceptionCompanyId(): void
    {
        $this->authorizationChecker
            ->expects(self::never())
            ->method('isGranted')
        ;

        $this->expectException(AccessDeniedException::class);

        $this->assertHelper->assertUserIsCompanyManagerOrAdmin(null);
    }

    public function testUserIsCompanyAdminOrManagerIsManager(): void
    {
        $this->authorizationChecker
            ->expects(self::atLeast(2))
            ->method('isGranted')
            ->withConsecutive([CompanyVoter::ADMIN, '12345'], [CompanyVoter::MANAGER, '12345'])
            ->willReturnOnConsecutiveCalls(false, true)
        ;

        $this->assertHelper->assertUserIsCompanyManagerOrAdmin('12345');
    }

    public function testUserIsCompanyAdminOrManagerIsAdmin(): void
    {
        $this->authorizationChecker
            ->expects(self::once())
            ->method('isGranted')
            ->with(CompanyVoter::ADMIN, '12345')
            ->willReturn(true)
        ;

        $this->assertHelper->assertUserIsCompanyManagerOrAdmin('12345');
    }
}
