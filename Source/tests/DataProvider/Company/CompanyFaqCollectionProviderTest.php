<?php

declare(strict_types=1);

namespace Tests\DataProvider\Company;

use App\DataProvider\Company\CompanyFaqCollectionProvider;
use App\Entity\Company;
use App\Security\AuthorizationAssertHelper;
use App\Service\Company\CompanyService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Faq;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class CompanyFaqCollectionProviderTest extends TestCase
{
    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var AuthorizationAssertHelper|MockObject */
    private AuthorizationAssertHelper|MockObject $authorizationAssertHelper;

    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    private CompanyFaqCollectionProvider $provider;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->authorizationAssertHelper = $this->createMock(AuthorizationAssertHelper::class);
        $this->companyService = $this->createMock(CompanyService::class);

        $this->provider = new CompanyFaqCollectionProvider($this->companyService);
        $this->provider->setRequestStack($this->requestStack);
        $this->provider->setAuthorizationAssertHelper($this->authorizationAssertHelper);
    }

    protected function tearDown(): void
    {
        unset(
            $this->provider,
            $this->companyService,
            $this->authorizationAssertHelper,
            $this->requestStack,
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
        yield [Company::class, 'get-company-faqs.as_manager', true];
        yield [Company::class, 'get', false];
        yield [\stdClass::class, 'get-company-faqs', false];
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

        $company
            ->expects(self::once())
            ->method('getFaqs')
            ->willReturn([$this->createMock(Faq::class)])
        ;

        $this->provider->getCollection(Company::class);
    }
}
