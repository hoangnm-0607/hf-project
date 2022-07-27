<?php

declare(strict_types=1);

namespace Tests\DataProvider\Company;

use App\DataProvider\Company\CompanyCustomFieldsCollectionProvider;
use App\Entity\Company;
use App\Security\AuthorizationAssertHelper;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CompanyCustomFieldsCollectionProviderTest extends TestCase
{
    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    private CompanyCustomFieldsCollectionProvider $provider;

    protected function setUp(): void
    {
        $this->companyService = $this->createMock(CompanyService::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);

        $this->provider = new CompanyCustomFieldsCollectionProvider($this->companyService);
        $this->provider->setRequestStack($this->requestStack);
        $this->provider->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
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
        yield [Company::class, 'get-company-custom-fields.as_manager', true];
        yield [Company::class, 'get', false];
        yield [\stdClass::class, 'get-company-custom-fields', false];
    }

    public function testGetCollection(): void
    {
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

        $this->companyService
            ->expects(self::once())
            ->method('findOneOrThrowException')
            ->with($companyId)
            ->willReturn($company)
        ;

        $this->companyService
            ->expects(self::once())
            ->method('prepareCustomFields')
            ->with($company)
            ->willReturn(['data'])
        ;

        $result = $this->provider->getCollection(Company::class, 'get-company-custom-fields');
        self::assertSame(['data'], $result);
    }
}
