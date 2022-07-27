<?php

declare(strict_types=1);

namespace Tests\Service\EndUser;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\EndUser;
use App\Service\Company\CompanyService;
use App\Service\EndUser\EndUserManager;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Service\FolderService;
use App\Service\I18NService;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\EndUser\Listing;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EndUserBulkUploadServiceTest extends TestCase
{
    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    /** @var Filesystem|MockObject */
    private Filesystem|MockObject $filesystem;

    /** @var CompanyService|MockObject */
    private CompanyService|MockObject $companyService;

    /** @var FolderService|MockObject */
    private FolderService|MockObject $folderService;

    /** @var EndUserManager|MockObject */
    private EndUserManager|MockObject $endUserManager;

    /** @var MockObject|I18NService */
    private MockObject|I18NService $i18NService;

    private EndUserBulkUploadService $service;

    protected function setUp(): void
    {
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->companyService = $this->createMock(CompanyService::class);
        $this->folderService = $this->createMock(FolderService::class);
        $this->endUserManager = $this->createMock(EndUserManager::class);
        $this->i18NService = $this->createMock(I18NService::class);

        $this->service = new EndUserBulkUploadService($this->filesystem, $this->companyService, $this->folderService, $this->endUserManager);
        $this->service->setValidator($this->validator);
        $this->service->setI18NService($this->i18NService);

    }

    protected function tearDown(): void
    {
        unset(
            $this->validator,
            $this->filesystem,
            $this->companyService,
            $this->folderService,
            $this->service,
            $this->endUserManager,
            $this->i18NService,
        );
    }

    public function testRemoveFileException(): void
    {
        $fileId = '132';

        $companyId = 777;

        $this->expectException(NotFoundHttpException::class);

        $this->service->removeEndUserUploadedFile($companyId, $fileId);
    }

    public function testCreateUsersListFile(): void
    {
        $user = $this->createMock(EndUser::class);

        $this->filesystem
            ->expects(self::once())
            ->method('tempnam')
            ->with('users-list', 'users-list')
            ->willReturn('/tmp/test-file-0x007')
        ;

        $rDate = $this->createMock(Carbon::class);
        $bDate = $this->createMock(Carbon::class);

        $bDate->expects(self::once())->method('format')->with('Y-m-d')->willReturn('2000-05-05');
        $listing = new Listing();
        $listing->setData([$user]);

        $user->expects(self::once())->method('getFirstname')->willReturn('f-name');
        $user->expects(self::once())->method('getLastname')->willReturn('l-name');
        $user->expects(self::once())->method('getStatus')->willReturn('active');
        $user->expects(self::once())->method('getGender')->willReturn('male');
        $user->expects(self::once())->method('getDateOfBirth')->willReturn($bDate);
        $user->expects(self::once())->method('getRegistrationDate')->willReturn($rDate);

        $response = $this->service->createUsersListCsvFile($listing);
        self::assertSame('/tmp/test-file-0x007', $response);
    }
}
