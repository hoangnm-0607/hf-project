<?php

declare(strict_types=1);

namespace Tests\Controller\EndUser;

use App\Controller\EndUser\EndUserDeleteController;
use App\Entity\EndUser;
use App\Service\EndUser\EndUserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class EndUserDeleteControllerTest extends TestCase
{
    /** @var EndUserManager|MockObject */
    private EndUserManager|MockObject $userManager;

    private EndUserDeleteController $controller;

    protected function setUp(): void
    {
        $this->userManager = $this->createMock(EndUserManager::class);

        $this->controller = new EndUserDeleteController($this->userManager);
    }

    protected function tearDown(): void
    {
        unset(
            $this->controller,
            $this->userManager,
        );
    }

    public function testInvoke(): void
    {
        $endUserId = 123;

        $user = $this->createMock(EndUser::class);

        $this->userManager
            ->expects(self::once())
            ->method('findOneOrThrowException')
            ->with($endUserId)
            ->willReturn($user)
        ;

        $this->userManager
            ->expects(self::once())
            ->method('removeUser')
            ->with($user)
        ;

        $response = $this->controller->__invoke($endUserId);
        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
