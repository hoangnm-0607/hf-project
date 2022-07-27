<?php

declare(strict_types=1);

namespace Tests\Service\Company;

use App\Dto\Company\CompanyCustomFieldDto;
use App\Repository\Company\CompanyRepository;
use App\Repository\Documents\DocumentsRepository;
use App\Service\Company\CompanyService;
use App\Service\FolderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Company;
use Pimcore\Model\DataObject\CompanyCustomField;
use Pimcore\Model\DataObject\Folder as DataObjectFolder;
use Pimcore\Model\DataObject\Localizedfield;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CompanyServiceTest extends TestCase
{
    /** @var CompanyRepository|MockObject */
    private CompanyRepository|MockObject $companyRepository;

    /** @var FolderService|MockObject */
    private FolderService|MockObject $folderService;

    /** @var Filesystem|MockObject */
    private Filesystem|MockObject $filesystem;

    private DocumentsRepository|MockObject $documentsRepository;

    private CompanyService|MockObject $service;

    protected function setUp(): void
    {
        $this->companyRepository = $this->createMock(CompanyRepository::class);
        $this->folderService = $this->createMock(FolderService::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->documentsRepository = $this->createMock(DocumentsRepository::class);

        $this->service = new CompanyService($this->companyRepository, $this->folderService, $this->filesystem, $this->documentsRepository, ['en', 'de']);
    }

    protected function tearDown(): void
    {
        unset(
            $this->service,
            $this->filesystem,
            $this->companyRepository,
            $this->folderService,
            $this->documentsRepository,
        );
    }

    public function testFindCompanyWithException(): void
    {
        $companyId = '123';

        $this->companyRepository
            ->expects(self::once())
            ->method('findOneSingleCompanyById')
            ->with($companyId)
            ->willReturn(null)
        ;

        $this->expectException(NotFoundHttpException::class);

        $this->service->findOneOrThrowException($companyId);
    }

    public function testFindCompany(): void
    {
        $companyId = '123';
        $company = $this->createMock(Company::class);
        $this->companyRepository
            ->expects(self::once())
            ->method('findOneSingleCompanyById')
            ->with($companyId)
            ->willReturn($company)
        ;

        $result = $this->service->findOneOrThrowException($companyId);
        self::assertSame($company, $result);
    }

    public function testFindCompanyByIds(): void
    {
        $companyIds = ['123'];

        $companyListing = $this->createMock(Company\Listing::class);

        $this->companyRepository
            ->expects(self::once())
            ->method('findAllByIds')
            ->with($companyIds)
            ->willReturn($companyListing)
        ;

        $result = $this->service->findAllByIds($companyIds);
        self::assertInstanceOf(Company\Listing::class, $result);
    }

    public function testCreateCustomFieldFromDto(): void
    {
        $dto = $this->createMock(CompanyCustomFieldDto::class);
        $company = $this->createMock(Company::class);
        $folder = $this->createMock(DataObjectFolder::class);

        $dto->key = 'key';
        $dto->inputType = 'string';

        $this->folderService
            ->expects(self::once())
            ->method('getOrCreateCustomFieldsSubFolderForCompany')
            ->with($company)
            ->willReturn($folder)
        ;

        $this->service->createCustomFieldFromDto($dto, $company);
    }

    public function testCrepareCustomFields(): void
    {
        $company = $this->createMock(Company::class);
        $customField = $this->createMock(CompanyCustomField::class);
        $localized = new Localizedfield(['en' => 'en', 'de' => 'de']);

        $company
            ->expects(self::once())
            ->method('getCompanyCustomFields')
            ->willReturn([$customField])
        ;

        $customField
            ->expects(self::once())
            ->method('getLocalizedfields')
            ->willReturn($localized)
        ;

        $customField
            ->expects(self::once())
            ->method('getKey')
            ->willReturn('key')
        ;

        $customField
            ->expects(self::atLeast(2))
            ->method('getName')
            ->withConsecutive(['en'], ['de'])
            ->willReturnOnConsecutiveCalls('en-name', 'de-name')
        ;

        $customField
            ->expects(self::once())
            ->method('getInputType')
            ->willReturn('string')
        ;

        $response = $this->service->prepareCustomFields($company);
        self::assertSame('string', $response[0]->inputType);
        self::assertSame('key', $response[0]->key);
        self::assertSame(
            [
            'en' => 'en-name',
            'de' => 'de-name',
            ],
            $response[0]->name
        );
    }

    public function testCreateTemplateUsersFileForCompany(): void
    {
        $companyId = 777;
        $company = $this->createMock(Company::class);

        $this->companyRepository
            ->expects(self::once())
            ->method('findOneSingleCompanyById')
            ->with($companyId)
            ->willReturn($company)
        ;

        $company
            ->expects(self::once())
            ->method('getCompanyCustomFields')
            ->willReturn([])
        ;

        $filename = '/tmp/company-777.asdfdf';

        $this->filesystem
            ->expects(self::once())
            ->method('tempnam')
            ->with('company', 'company-777.')
            ->willReturn($filename)
        ;

        $result = $this->service->createTemplateUsersFileForCompany($companyId);
        self::assertSame($filename, $result);
    }
}
