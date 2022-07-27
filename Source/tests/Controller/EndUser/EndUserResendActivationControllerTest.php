<?php

declare(strict_types=1);

namespace Tests\Controller\EndUser;

use App\Controller\EndUser\EndUserResendActivationController;
use App\Entity\EndUser;
use App\Message\EndUserActivationMessage;
use App\Service\Company\CompanyService;
use App\Service\File\PdfFileService;
use App\Service\I18NService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Document\Printpage;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

final class EndUserResendActivationControllerTest extends TestCase
{
    /** @var PdfFileService|MockObject */
    private PdfFileService|MockObject $pdfFileService;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var MessageBusInterface|MockObject */
    private MessageBusInterface|MockObject $messageBus;

    private I18NService|MockObject $i18NService;

    private string $filename;

    private EndUserResendActivationController $controller;

    protected function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->pdfFileService = $this->createMock(PdfFileService::class);
        $this->i18NService = $this->createMock(I18NService::class);

        $this->controller = new EndUserResendActivationController($this->pdfFileService, $this->companyService);
        $this->controller->setMessageBusDispatcher($this->messageBus);
        $this->controller->setI18NService($this->i18NService);

        $this->filename = '/tmp/file.pdf';

        $handle = fopen($this->filename, 'w');
        fwrite($handle, 'test');
        fclose($handle);
    }

    protected function tearDown(): void
    {
        unlink($this->filename);

        unset(
            $this->messageBus,
            $this->companyService,
            $this->pdfFileService,
            $this->controller,
            $this->i18NService,
        );
    }

    public function testInvokeJson(): void
    {
        $request = $this->createMock(Request::class);
        $attr = $this->createMock(ParameterBag::class);
        $headers = $this->createMock(HeaderBag::class);

        $headers
            ->expects(self::once())
            ->method('get')
            ->with('content-type')
            ->willReturn('application/json')
        ;

        $request->headers = $headers;

        $request->attributes = $attr;
        $endUser = $this->createMock(EndUser::class);

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('isPermittedToActivate')
            ->willReturn(true)
        ;

        $html = $this->createMock(Printpage::class);

        $this->companyService
            ->expects(self::once())
            ->method('findEndUserActivationTemplate')
            ->willReturn($html)
        ;

        $this->pdfFileService
            ->expects(self::once())
            ->method('createEndUserActivationPdfFile')
            ->with($html, $endUser)
            ->willReturn($this->filename)
        ;

        $endUser
            ->expects(self::once())
            ->method('getPrivateEmail')
            ->willReturn('enduser@gmail.com')
        ;

        $endUser
            ->expects(self::never())
            ->method('getBusinessEmail')
        ;

        $this->messageBus
            ->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(EndUserActivationMessage::class))
        ;

        $response = $this->controller->__invoke($request);
        self::assertInstanceOf(JsonResponse::class, $response);
    }

    public function testInvokeJsonException(): void
    {
        $request = $this->createMock(Request::class);
        $attr = $this->createMock(ParameterBag::class);
        $headers = $this->createMock(HeaderBag::class);

        $headers
            ->expects(self::once())
            ->method('get')
            ->with('content-type')
            ->willReturn('application/json')
        ;

        $request->headers = $headers;

        $request->attributes = $attr;
        $endUser = $this->createMock(EndUser::class);

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('isPermittedToActivate')
            ->willReturn(true)
        ;

        $html = $this->createMock(Printpage::class);

        $this->companyService
            ->expects(self::once())
            ->method('findEndUserActivationTemplate')
            ->willReturn($html)
        ;

        $this->pdfFileService
            ->expects(self::once())
            ->method('createEndUserActivationPdfFile')
            ->with($html, $endUser)
            ->willReturn($this->filename)
        ;

        $endUser
            ->expects(self::once())
            ->method('getPrivateEmail')
            ->willReturn(null)
        ;

        $endUser
            ->expects(self::once())
            ->method('getBusinessEmail')
            ->willReturn(null)
        ;

        $this->messageBus
            ->expects(self::never())
            ->method('dispatch')
            ->with(self::isInstanceOf(EndUserActivationMessage::class))
        ;

        $response = $this->controller->__invoke($request);
        self::assertSame(415, $response->getStatusCode());
    }

    public function testInvokeFile(): void
    {
        $request = $this->createMock(Request::class);
        $attr = $this->createMock(ParameterBag::class);

        $request->attributes = $attr;
        $endUser = $this->createMock(EndUser::class);

        $headers = $this->createMock(HeaderBag::class);

        $headers
            ->expects(self::once())
            ->method('get')
            ->with('content-type')
            ->willReturn('application/pdf')
        ;

        $request->headers = $headers;

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('isPermittedToActivate')
            ->willReturn(true)
        ;

        $html = $this->createMock(Printpage::class);

        $this->companyService
            ->expects(self::once())
            ->method('findEndUserActivationTemplate')
            ->willReturn($html)
        ;

        $this->pdfFileService
            ->expects(self::once())
            ->method('createEndUserActivationPdfFile')
            ->with($html, $endUser)
            ->willReturn($this->filename)
        ;

        $endUser
            ->expects(self::once())
            ->method('getPrivateEmail')
            ->willReturn(null)
        ;

        $endUser
            ->expects(self::once())
            ->method('getBusinessEmail')
            ->willReturn(null)
        ;

        $this->messageBus
            ->expects(self::never())
            ->method('dispatch')
        ;

        $response = $this->controller->__invoke($request);
        self::assertInstanceOf(Response::class, $response);
    }

    public function testInvokeLogicException(): void
    {
        $request = $this->createMock(Request::class);
        $attr = $this->createMock(ParameterBag::class);

        $request->attributes = $attr;
        $endUser = null;

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($endUser)
        ;

        $this->expectException(\LogicException::class);

        $this->controller->__invoke($request);
    }

    public function testInvokeBadRequestException(): void
    {
        $request = $this->createMock(Request::class);
        $attr = $this->createMock(ParameterBag::class);

        $request->attributes = $attr;
        $endUser = $this->createMock(EndUser::class);

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('isPermittedToActivate')
            ->willReturn(false)
        ;

        $this->expectException(BadRequestHttpException::class);

        $this->controller->__invoke($request);
    }

    public function testInvokeBadRequestExceptionLast(): void
    {
        $request = $this->createMock(Request::class);
        $attr = $this->createMock(ParameterBag::class);

        $request->attributes = $attr;
        $endUser = $this->createMock(EndUser::class);

        $headers = $this->createMock(HeaderBag::class);

        $headers
            ->expects(self::once())
            ->method('get')
            ->with('content-type')
            ->willReturn('application/csv')
        ;

        $request->headers = $headers;

        $attr
            ->expects(self::once())
            ->method('get')
            ->with('data')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('isPermittedToActivate')
            ->willReturn(true)
        ;

        $html = $this->createMock(Printpage::class);

        $this->companyService
            ->expects(self::once())
            ->method('findEndUserActivationTemplate')
            ->willReturn($html)
        ;

        $this->expectException(BadRequestHttpException::class);

        $this->controller->__invoke($request);
    }
}
