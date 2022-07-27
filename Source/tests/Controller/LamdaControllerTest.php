<?php

namespace Tests\Controller;

use App\Controller\LamdaController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LamdaControllerTest extends TestCase
{
    private Request|MockObject $requestMock;
    private LamdaController $lamdaController;

    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(Request::class);
        $this->lamdaController = new LamdaController();
    }

    public function testValidatePartnerIdAction_ReturnsErrorCombination()
    {
        $this->requestMock->method('get')->willReturn(42, 42);
        $output = $this->lamdaController->validatePartnerIdAction($this->requestMock);

        self::assertEquals([
            'error' => 'Unknown partner_id/start_code combination'
        ], json_decode($output->getContent(), true));
        self::assertEquals(Response::HTTP_NOT_FOUND, $output->getStatusCode());
    }

    public function testPartnerNameAction_ReturnsErrorPublicId()
    {
        $output = $this->lamdaController->partnerNameAction(99999);

        self::assertEquals([
            'error' => 'Unknown publicId'
        ], json_decode($output->getContent(), true));
        self::assertEquals(Response::HTTP_NOT_FOUND, $output->getStatusCode());
    }
}
