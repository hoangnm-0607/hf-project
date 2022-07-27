<?php

declare(strict_types=1);

namespace Tests\Security\Validator;

use App\Exception\MissingPublicIdException;
use App\Exception\TokenIdMismatchException;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\InMemoryUserReaderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUser;

final class InMemoryUserValidatorTest extends TestCase
{
    /** @var Security|MockObject */
    private Security|MockObject $security;

    /** @var InMemoryUserReaderService|MockObject */
    private InMemoryUserReaderService|MockObject $service;

    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    private InMemoryUserValidator $validator;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->service = $this->createMock(InMemoryUserReaderService::class);
        $this->requestStack = $this->createMock(RequestStack::class);

        $this->validator = new InMemoryUserValidator($this->security, $this->service);
        $this->validator->setRequestStack($this->requestStack);
    }

    protected function tearDown(): void
    {
        unset(
            $this->security,
            $this->service,
            $this->validator,
            $this->requestStack,
        );
    }

    public function testValidateTokenAndApiUserIdNotUser(): void
    {
        $this->security
            ->expects(self::once())
            ->method('getUser')
            ->willReturn(null)
        ;

        $this->expectException(TokenIdMismatchException::class);

        $this->validator->validateTokenAndApiUserId('userId');
    }

    public function testValidateTokenAndApiUserIdWrongId(): void
    {
        $tokenUser = new InMemoryUser('userId1', null);

        $this->security
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($tokenUser)
        ;

        $this->expectException(TokenIdMismatchException::class);

        $this->validator->validateTokenAndApiUserId('userId');
    }

    public function testValidateTokenAndApiUserValid(): void
    {
        $tokenUser = new InMemoryUser('userId', null);

        $this->security
            ->expects(self::once())
            ->method('getUser')
            ->willReturn($tokenUser)
        ;

        $this->validator->validateTokenAndApiUserId('userId');
    }

    public function testValidateAccessToRequestBackwardCompatibility(): void
    {
        $decodedToken = [
          [
              'publicId' => '1234',
          ]
        ];

        $this->service
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($decodedToken)
        ;

        $result = $this->validator->validateTokenAndAccessToRequestedEntityId('1234');
        self::assertSame('1234', $result);
    }

    /**
     * @param array       $decodedToken
     * @param string      $entityId
     * @param string|null $role
     *
     * @dataProvider dataProviderValidAccess
     */
    public function testValidateAccessToRequest(array $decodedToken, string $entityId, ?string $role): void
    {
        $this->service
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($decodedToken)
        ;

        $result = $this->validator->validateTokenAndAccessToRequestedEntityId($entityId, 'companyId', $role);
        self::assertSame($entityId, $result);
    }

    public static function dataProviderValidAccess(): iterable
    {
        yield [
            [
                [
                    'companyId' => '12345',
                    'role' => 'Admin',
                ]
            ],
            '12345',
            'Admin',
        ];

        yield [
            [
                [
                    'companyId' => '12345',
                ]
            ],
            '12345',
            null,
        ];
    }

    public function testValidateAccessToRequestMissingPublicIdException(): void
    {
        $attr = new ParameterBag();
        $request = $this->createMock(Request::class);
        $request->attributes = $attr;

        $this->requestStack
            ->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $this->expectException(MissingPublicIdException::class);

        $this->service
            ->expects(self::never())
            ->method('getUserIdentifier')
        ;

        $this->validator->validateTokenAndAccessToRequestedEntityId(null,'companyId');
    }

    public function testValidateAccessToRequestTokenIdMismatchException(): void
    {
        $decodedToken = [
            [
                'companyId' => '12345',
            ]
        ];

        $this->service
            ->expects(self::once())
            ->method('getUserIdentifier')
            ->willReturn($decodedToken)
        ;

        $this->expectException(TokenIdMismatchException::class);

        $this->validator->validateTokenAndAccessToRequestedEntityId('12345');
    }
}
