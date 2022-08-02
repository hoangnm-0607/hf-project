<?php

declare(strict_types=1);

namespace Tests\Service\CAS;

use App\DataTransformer\Populator\CAS\CASPopulatorResolver;
use App\Dto\CAS\CasDtoInterface;
use App\Service\CAS\CasCommunicationService;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\EndUser;
use Sentry\ClientInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CasCommunicationServiceTest extends TestCase
{
    private CASPopulatorResolver $populatorResolver;
    private MessageBusInterface $bus;
    private HttpClientInterface $httpClient;
    private string $casApiPlatformUri = 'http://casapi';
    private ClientInterface $sentryClient;

    private CasCommunicationService $service;

    protected function setUp(): void
    {
        $this->populatorResolver = $this->createMock(CASPopulatorResolver::class);
        $this->bus = $this->createMock(MessageBusInterface::class);
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->sentryClient = $this->createMock(ClientInterface::class);

        $this->service = new CasCommunicationService($this->populatorResolver, $this->bus, $this->httpClient, $this->casApiPlatformUri);
        $this->service->setSentryClient($this->sentryClient);
    }

    protected function tearDown(): void
    {
        unset(
            $this->service,
            $this->populatorResolver,
            $this->bus,
            $this->httpClient,
            $this->sentryClient,
        );
    }

    public function testCreateCasCompany(): void
    {
        $company = $this->createMock(Company::class);

        $casUri = $this->casApiPlatformUri.'/companies';

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populatorResolver
            ->expects(self::once())
            ->method('populate')
            ->with($company)
            ->willReturn($dto)
        ;

        $response = $this->createMock(ResponseInterface::class);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $casUri, ['json' => $dto])
            ->willReturn($response)
        ;

        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(201)
        ;

        $response
            ->expects(self::once())
            ->method('toArray')
        ;

        $this->service->createCasDataForNewCompany($company);
    }

    public function testCreateCasCompanyRequestException(): void
    {
        $company = $this->createMock(Company::class);

        $casUri = $this->casApiPlatformUri.'/companies';

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populatorResolver
            ->expects(self::once())
            ->method('populate')
            ->with($company)
            ->willReturn($dto)
        ;

        $e = new TransportException();

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $casUri, ['json' => $dto])
            ->willThrowException($e)
        ;

        $this->sentryClient
            ->expects(self::once())
            ->method('captureException')
            ->with($e)
        ;

        $this->service->createCasDataForNewCompany($company);
    }

    public function testCreateCasCompanyWithBadStatusCode(): void
    {
        $company = $this->createMock(Company::class);

        $casUri = $this->casApiPlatformUri.'/companies';

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populatorResolver
            ->expects(self::once())
            ->method('populate')
            ->with($company)
            ->willReturn($dto)
        ;

        $response = $this->createMock(ResponseInterface::class);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $casUri, ['json' => $dto])
            ->willReturn($response)
        ;

        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(500)
        ;

        $this->sentryClient
            ->expects(self::once())
            ->method('captureMessage')
        ;

        $response
            ->expects(self::never())
            ->method('toArray')
        ;

        $this->service->createCasDataForNewCompany($company);
    }

    public function testCreateCasEndUser(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $casUri = $this->casApiPlatformUri.'/customers';

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populatorResolver
            ->expects(self::once())
            ->method('populate')
            ->with($endUser)
            ->willReturn($dto)
        ;

        $response = $this->createMock(ResponseInterface::class);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $casUri, ['json' => $dto])
            ->willReturn($response)
        ;

        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(201)
        ;

        $response
            ->expects(self::once())
            ->method('toArray')
        ;

        $this->service->createCasDataForNewEndUser($endUser);
    }

    public function testCreateCasEndUserRequestException(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $casUri = $this->casApiPlatformUri.'/customers';

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populatorResolver
            ->expects(self::once())
            ->method('populate')
            ->with($endUser)
            ->willReturn($dto)
        ;

        $e = new TransportException();

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $casUri, ['json' => $dto])
            ->willThrowException($e)
        ;

        $this->sentryClient
            ->expects(self::once())
            ->method('captureException')
            ->with($e)
        ;

        $this->service->createCasDataForNewEndUser($endUser);
    }

    public function testCreateCasEndUserWithBadStatusCode(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $casUri = $this->casApiPlatformUri.'/customers';

        $dto = $this->createMock(CasDtoInterface::class);

        $this->populatorResolver
            ->expects(self::once())
            ->method('populate')
            ->with($endUser)
            ->willReturn($dto)
        ;

        $response = $this->createMock(ResponseInterface::class);

        $this->httpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_POST, $casUri, ['json' => $dto])
            ->willReturn($response)
        ;

        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(500)
        ;

        $this->sentryClient
            ->expects(self::once())
            ->method('captureMessage')
        ;

        $response
            ->expects(self::never())
            ->method('toArray')
        ;

        $this->service->createCasDataForNewEndUser($endUser);
    }
}
