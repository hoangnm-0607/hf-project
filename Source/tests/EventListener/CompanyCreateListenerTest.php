<?php

declare(strict_types=1);

namespace Tests\EventListener;

use App\EventListener\CompanyCreateListener;
use App\Service\CAS\CasCommunicationService;
use App\Service\FolderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\Element\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CompanyCreateListenerTest extends TestCase
{
    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    private FolderService|MockObject $folderService;
    private CasCommunicationService|MockObject $casCommunicationService;

    protected TranslatorInterface|MockObject $translator;

    private CompanyCreateListener $listener;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->folderService = $this->createMock(FolderService::class);
        $this->casCommunicationService = $this->createMock(CasCommunicationService::class);
        $this->translator = $this->createMock(TranslatorInterface::class);


        $this->listener = new CompanyCreateListener($this->folderService, $this->casCommunicationService);
        $this->listener->setRequestStack($this->requestStack);
        $this->listener->setTranslator($this->translator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->folderService,
            $this->casCommunicationService,
            $this->listener,
            $this->requestStack,
            $this->translator,
        );
    }

    public function testCreateCasCompany(): void
    {
        $event = $this->createMock(DataObjectEvent::class);
        $company = $this->createMock(Company::class);
        $event
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getCasCompanyId')
            ->willReturn(null)
        ;

        $event
            ->expects(self::once())
            ->method('getArguments')
            ->willReturn([])
        ;

        $company
            ->expects(self::once())
            ->method('isPublished')
            ->willReturn(true)
        ;

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('get')
            ->with('task')
            ->willReturn('publish')
        ;

        $this->requestStack
            ->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $casData = [
            'companyId' => 123,
        ];

        $this->casCommunicationService
            ->expects(self::once())
            ->method('createCasDataForNewCompany')
            ->with($company)
            ->willReturn($casData)
        ;

        $company
            ->expects(self::once())
            ->method('setCasCompanyId')
            ->with(123)
        ;

        $company
            ->expects(self::once())
            ->method('save')
        ;

        $this->listener->createCasCompany($event);
    }

    public function testCreateCasCompanyException(): void
    {
        $event = $this->createMock(DataObjectEvent::class);
        $company = $this->createMock(Company::class);
        $event
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getCasCompanyId')
            ->willReturn(null)
        ;

        $event
            ->expects(self::once())
            ->method('getArguments')
            ->willReturn([])
        ;

        $company
            ->expects(self::once())
            ->method('isPublished')
            ->willReturn(true)
        ;

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('get')
            ->with('task')
            ->willReturn('publish')
        ;

        $this->requestStack
            ->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $casData = null;

        $this->casCommunicationService
            ->expects(self::once())
            ->method('createCasDataForNewCompany')
            ->with($company)
            ->willReturn($casData)
        ;

        $company
            ->expects(self::never())
            ->method('setCasCompanyId')
        ;

        $company
            ->expects(self::once())
            ->method('setPublished')
            ->with(false)
        ;

        $company
            ->expects(self::once())
            ->method('save')
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('admin.object.company.message.cantCasCreate', [], 'admin')
        ;

        $this->expectException(ValidationException::class);

        $this->listener->createCasCompany($event);
    }

    public function testCreateCasCompanyIsAutoSave(): void
    {
        $event = $this->createMock(DataObjectEvent::class);
        $company = $this->createMock(Company::class);
        $event
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getCasCompanyId')
            ->willReturn(null)
        ;

        $event
            ->expects(self::once())
            ->method('getArguments')
            ->willReturn(['isAutoSave' => true])
        ;

        $company
            ->expects(self::never())
            ->method('isPublished')
        ;

        $company
            ->expects(self::never())
            ->method('save')
        ;

        $this->listener->createCasCompany($event);
    }
}
