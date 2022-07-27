<?php

declare(strict_types=1);

namespace Tests\DataProvider\Company;

use App\DataProvider\Company\CompanyEndUserListCollectionProvider;
use App\Entity\Company;
use App\Repository\EndUser\EndUserRepository;
use App\Security\AuthorizationAssertHelper;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\EndUser\Listing;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CompanyEndUserListCollectionProviderTest extends TestCase
{
    /** @var EndUserRepository|MockObject */
    private EndUserRepository|MockObject $userRepository;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private CompanyEndUserListCollectionProvider $provider;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(EndUserRepository::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);


        $this->provider = new CompanyEndUserListCollectionProvider($this->userRepository, $this->companyService, 10);
        $this->provider->setRequestStack($this->requestStack);
        $this->provider->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->userRepository,
            $this->companyService,
            $this->provider,
            $this->requestStack,
            $this->authorizationAssertHelper,
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
        yield [Company::class, 'get-company-end-users.as_manager', true];
        yield [Company::class, 'get', false];
        yield [\stdClass::class, 'get-company-end-users', false];
    }

    public function testGetCollection(): void
    {
        $context = [
            'filters' => [
                'page' => 1,
                'limit' => 30,
            ],
        ];

        $companyId = 777;

        $request = $this->createMock(Request::class);
        $attributes = $this->createMock(ParameterBag::class);
        $request->attributes = $attributes;
        $attributes
            ->expects(self::once())
            ->method('get')
            ->with('companyId')
            ->willReturn($companyId)
        ;

        $this->requestStack
            ->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $this->authorizationAssertHelper
            ->expects(self::once())
            ->method('assertUserIsCompanyManagerOrAdmin')
            ->with($companyId)
        ;

        $company = $this->createMock(Company::class);

        $users = $this->createMock(Listing::class);

        $this->companyService
            ->expects(self::once())
            ->method('findOneOrThrowException')
            ->with($companyId)
            ->willReturn($company)
        ;

        $this->userRepository
            ->expects(self::once())
            ->method('findByCompanyForExportList')
            ->with($company)
            ->willReturn($users)
        ;

        $users
            ->expects(self::once())
            ->method('setOffset')
            ->with(0)
        ;

        $users
            ->expects(self::once())
            ->method('setLimit')
            ->with(30)
        ;

        $result = $this->provider->getCollection(Company::class, 'get-company-end-users', $context);
        self::assertInstanceOf(Listing::class, $result);
    }
}
