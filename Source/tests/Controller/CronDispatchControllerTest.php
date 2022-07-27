<?php

namespace Tests\Controller;

use App\Controller\CronDispatchController;
use App\Service\CronService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class CronDispatchControllerTest extends TestCase
{
    private Request|MockObject $requestMock;
    private KernelInterface $kernel;
    private CronDispatchController $cronDispatchController;

    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(Request::class);
        $this->kernel = $this->createMock(KernelInterface::class);
        $cronService = $this->createMock(CronService::class);
        $this->cronDispatchController = new CronDispatchController($cronService);
    }

    public function testDispatchAction()
    {
        // test with not existing cronjob
        $output = $this->cronDispatchController->dispatchAction('not-existing-job', $this->kernel, $this->requestMock);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $output->getStatusCode());

        // test with existing cronjob
        $output = $this->cronDispatchController->dispatchAction(CronDispatchController::DISPATCHABLE_JOBS[0], $this->kernel, $this->requestMock);
        self::assertEquals(Response::HTTP_ACCEPTED, $output->getStatusCode());
    }
}
