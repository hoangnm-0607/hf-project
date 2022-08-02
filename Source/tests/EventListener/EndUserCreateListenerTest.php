<?php

declare(strict_types=1);

namespace Tests\EventListener;

use App\EventListener\EndUserCreateListener;
use App\Service\CAS\CasCommunicationService;
use App\Service\FolderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\EndUser;
use Pimcore\Model\Element\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

final class EndUserCreateListenerTest extends TestCase
{
    /** @var RequestStack|MockObject */
    private RequestStack|MockObject $requestStack;

    private FolderService|MockObject $folderService;
    private CasCommunicationService|MockObject $casCommunicationService;

    protected TranslatorInterface|MockObject $translator;

    private EndUserCreateListener $listener;

    protected function setUp(): void
    {
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->folderService = $this->createMock(FolderService::class);
        $this->casCommunicationService = $this->createMock(CasCommunicationService::class);
        $this->translator = $this->createMock(TranslatorInterface::class);


        $this->listener = new EndUserCreateListener($this->folderService, $this->casCommunicationService);
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
        $endUser = $this->createMock(EndUser::class);
        $event
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('getCasUserId')
            ->willReturn(null)
        ;

        $event
            ->expects(self::once())
            ->method('getArguments')
            ->willReturn([])
        ;

        $endUser
            ->expects(self::once())
            ->method('isPublished')
            ->willReturn(true)
        ;

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('get')
            ->withConsecutive(['task'], ['_route'])
            ->willReturnOnConsecutiveCalls('publish', 'api_end_users_create.as_manager_collection')
        ;

        $this->requestStack
            ->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $casData = [
            'customerId' => 123,
            'activationKey' => 'key',
            'publicId' => 'hash',
        ];

        $this->casCommunicationService
            ->expects(self::once())
            ->method('createCasDataForNewEndUser')
            ->with($endUser)
            ->willReturn($casData)
        ;

        $endUser
            ->expects(self::once())
            ->method('setCasUserId')
            ->with(123)
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('setActivationKey')
            ->with('key')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('setHashedUserId')
            ->with('hash')
        ;

        $endUser
            ->expects(self::once())
            ->method('save')
        ;

        $this->listener->createCasEndUser($event);
    }

    public function testCreateCasCompanyException(): void
    {
        $event = $this->createMock(DataObjectEvent::class);
        $endUser = $this->createMock(EndUser::class);
        $event
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('getCasUserId')
            ->willReturn(null)
        ;

        $event
            ->expects(self::once())
            ->method('getArguments')
            ->willReturn([])
        ;

        $endUser
            ->expects(self::once())
            ->method('isPublished')
            ->willReturn(true)
        ;

        $request = $this->createMock(Request::class);
        $request
            ->expects(self::once())
            ->method('get')
            ->withConsecutive(['task'], ['_route'])
            ->willReturnOnConsecutiveCalls('publish', 'api_end_users_create.as_manager_collection')
        ;


        $this->requestStack
            ->expects(self::once())
            ->method('getCurrentRequest')
            ->willReturn($request)
        ;

        $casData = null;

        $this->casCommunicationService
            ->expects(self::once())
            ->method('createCasDataForNewEndUser')
            ->with($endUser)
            ->willReturn($casData)
        ;

        $endUser
            ->expects(self::never())
            ->method('setCasUserId')
        ;

        $endUser
            ->expects(self::once())
            ->method('setPublished')
            ->with(false)
        ;

        $endUser
            ->expects(self::once())
            ->method('save')
        ;

        $this->translator
            ->expects(self::once())
            ->method('trans')
            ->with('admin.object.enduser.message.cantCasCreate', [], 'admin')
        ;

        $this->expectException(ValidationException::class);

        $this->listener->createCasEndUser($event);
    }

    public function testCreateCasCompanyIsAutoSave(): void
    {
        $event = $this->createMock(DataObjectEvent::class);
        $endUser = $this->createMock(EndUser::class);
        $event
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($endUser)
        ;

        $endUser
            ->expects(self::once())
            ->method('getCasUserId')
            ->willReturn(null)
        ;

        $event
            ->expects(self::once())
            ->method('getArguments')
            ->willReturn(['isAutoSave' => true])
        ;

        $endUser
            ->expects(self::never())
            ->method('isPublished')
        ;

        $endUser
            ->expects(self::never())
            ->method('save')
        ;

        $this->listener->createCasEndUser($event);
    }
}
