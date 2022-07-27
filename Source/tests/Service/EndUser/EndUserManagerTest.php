<?php

declare(strict_types=1);

namespace Tests\Service\EndUser;

use App\Dto\EndUser\EndUserInputDto;
use App\Entity\EndUser;
use App\Repository\EndUser\EndUserRepository;
use App\Service\Company\CompanyService;
use App\Service\EndUser\EndUserManager;
use App\Service\FolderService;
use App\Service\I18NService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\CompanyCustomField;
use Pimcore\Model\DataObject\Data\ObjectMetadata;
use Pimcore\Model\DataObject\Folder as DataObjectFolder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EndUserManagerTest extends TestCase
{
    /** @var FolderService|MockObject */
    private FolderService|MockObject $folderService;

    /** @var EndUserRepository|MockObject */
    private EndUserRepository|MockObject $userRepository;

    /** @var I18NService|MockObject */
    private I18NService|MockObject $i18NService;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    private EndUserManager $manager;

    protected function setUp(): void
    {
        $this->folderService = $this->createMock(FolderService::class);
        $this->i18NService = $this->createMock(I18NService::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->userRepository = $this->createMock(EndUserRepository::class);

        $this->manager = new EndUserManager($this->folderService, $this->companyService, $this->userRepository);
        $this->manager->setI18NService($this->i18NService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->i18NService,
            $this->manager,
            $this->folderService,
            $this->companyService,
            $this->userRepository,
        );
    }

    public function testPrepareCustomFields(): void
    {
        $endUser = $this->createMock(EndUser::class);

        $this->i18NService
            ->expects(self::once())
            ->method('getLanguageFromRequest')
            ->willReturn('en')
        ;

        $customField = $this->createMock(ObjectMetadata::class);
        $endUser
            ->expects(self::once())
            ->method('getCustomFields')
            ->willReturn([$customField])
        ;

        $customFieldObject = $this->createMock(CompanyCustomField::class);

        $customField
            ->expects(self::once())
            ->method('getObject')
            ->willReturn($customFieldObject)
        ;

        $customField
            ->expects(self::once())
            ->method('getData')
            ->willReturn(['fieldValue' => '123456'])
        ;

        $customFieldObject
            ->expects(self::once())
            ->method('getName')
            ->with('en')
            ->willReturn('employId')
        ;

        $result = $this->manager->prepareCustomFields($endUser);
        self::assertSame(['employId' => '123456'], $result);
    }

    public function testCreateEndUserForCompanyFromDto(): void
    {
        $company = $this->createMock(Company::class);
        $dto = $this->createMock(EndUserInputDto::class);
        $folder = $this->createMock(DataObjectFolder::class);
        $companyCustomFiled = $this->createMock(CompanyCustomField::class);

        $this->companyService
            ->expects(self::atLeast(2))
            ->method('findCustomFieldByKey')
            ->withConsecutive([[$companyCustomFiled], 'key'], [[$companyCustomFiled], 'null-key'])
            ->willReturnOnConsecutiveCalls($companyCustomFiled, null)
        ;

        $this->folderService
            ->expects(self::once())
            ->method('getOrCreateEndUsersFolderForCompany')
            ->with($company)
            ->willReturn($folder)
        ;

        $company
            ->expects(self::once())
            ->method('getCompanyCustomFields')
            ->willReturn([$companyCustomFiled])
        ;

        $dto->customFields = ['key' => 'value', 'null-key' => 'null-value'];
        $dto->businessEmail = 'email@gmail.com';
        $dto->privateEmail = 'email@gmail.com';
        $dto->phoneNumber = '123456';
        $dto->dateOfBirth = new \DateTime();
        $dto->firstName = 'fname';
        $dto->lastName = 'lname';
        $dto->gender = 'female';

        $user = $this->manager->createEndUserForCompanyFromDto($company, $dto);
        self::assertSame('fname', $user->getFirstname());
    }

    public function testRemoveUser(): void
    {
        $folderObject = $this->createMock(DataObjectFolder::class);
        $user = $this->createMock(EndUser::class);

        $this->folderService
            ->expects(self::once())
            ->method('getOrCreateEndUsersFolderForFolderName')
            ->with('Unassigned End Users')
            ->willReturn($folderObject)
        ;

        $user->expects(self::once())->method('setCompany')->with(null)->willReturn($user);
        $user->expects(self::once())->method('setCustomFields')->with(null)->willReturn($user);
        $user->expects(self::once())->method('setParent')->with($folderObject)->willReturn($user);
        $user->expects(self::once())->method('setStatus')->with('deleted')->willReturn($user);
        $user->expects(self::once())->method('save');

        $this->manager->removeUser($user);
    }

    public function testFindUser(): void
    {
        $userId = 123;
        $user = $this->createMock(EndUser::class);

        $this->userRepository
            ->expects(self::once())
            ->method('findOneById')
            ->with($userId)
            ->willReturn($user)
        ;

        $this->manager->findOneOrThrowException($userId);
    }

    public function testFindUserNotFound(): void
    {
        $userId = 123;

        $this->userRepository
            ->expects(self::once())
            ->method('findOneById')
            ->with($userId)
            ->willReturn(null)
        ;

        $this->expectException(NotFoundHttpException::class);

        $this->manager->findOneOrThrowException($userId);
    }
}
