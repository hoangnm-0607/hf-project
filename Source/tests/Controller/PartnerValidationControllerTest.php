<?php

namespace Tests\Controller;

use App\Controller\PartnerValidationController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PartnerValidationControllerTest extends TestCase
{
    private Request|MockObject $requestMock;
    private PartnerValidationController $partnerValidationController;

    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(Request::class);
        $this->partnerValidationController = new PartnerValidationController();
    }

    public function testValidatePartnerIdAction_ReturnsSuccessFalse()
    {
        $this->requestMock->method('get')->willReturn(99, 99);
        $output = $this->partnerValidationController->validatePartnerIdAction($this->requestMock);

        self::assertEquals([
            'success' => false
        ], json_decode($output->getContent(), true));
    }
}
