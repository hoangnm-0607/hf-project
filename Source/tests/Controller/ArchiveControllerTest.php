<?php

namespace Tests\Controller;

use App\Controller\ArchiveController;
use App\Service\ArchiveService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArchiveControllerTest extends TestCase
{
    private TranslatorInterface $translator;
    private Request|MockObject $requestMock;
    private ArchiveController $archiveController;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $archiveService = $this->createMock(ArchiveService::class);
        $this->archiveController = new ArchiveController($this->translator, $archiveService);
        $this->requestMock = $this->createMock(Request::class);
    }

    public function testArchiveAction_ReturnsFalse()
    {
        $this->requestMock->query = new ParameterBag(['id' => 999999999]);
        $output = $this->archiveController->archiveAction($this->requestMock);

        $expectedMessage = 'admin.object.message.archiveFailed.unknownObjectType';

        self::assertEquals([
            'canArchive' => false,
            'message' => $this->translator->trans($expectedMessage, [], 'admin')
        ], json_decode($output->getContent(), true));
    }
}
