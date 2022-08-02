<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Company;

use App\DataTransformer\Company\CompanyAssetsOutputDataTransformer;
use App\Dto\Company\CompanyAssetDto;
use App\Dto\Company\CompanyAssetsOutputDto;
use App\Entity\Company;
use App\Repository\Asset\AssetRepository;
use App\Service\FolderService;
use App\Service\I18NService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\CompanyFileCategory;

final class CompanyAssetsOutputDataTransformerTest extends TestCase
{
    /** @var FolderService|MockObject */
    private FolderService|MockObject $folderService;

    /** @var AssetRepository|MockObject */
    private AssetRepository|MockObject $assetRepository;

    /** @var I18NService|MockObject */
    private I18NService|MockObject $i18NService;

    private CompanyAssetsOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->folderService = $this->createMock(FolderService::class);
        $this->assetRepository = $this->createMock(AssetRepository::class);
        $this->i18NService = $this->createMock(I18NService::class);

        $this->transformer = new CompanyAssetsOutputDataTransformer($this->folderService, $this->assetRepository);
        $this->transformer->setI18NService($this->i18NService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->i18NService,
            $this->assetRepository,
            $this->folderService,
        );
    }

    /**
     * @param mixed  $dto
     * @param string $to
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation($dto, string $to, bool $supportResult): void
    {
        $result = $this->transformer->supportsTransformation($dto, $to, []);
        self::assertSame($supportResult, $result);
    }

    public function dataProviderSupportTransformation(): iterable
    {
        yield [$this->createMock(Company::class), CompanyAssetsOutputDto::class, true];
        yield [$this->createMock(Company::class), \stdClass::class, false];
        yield [null, CompanyAssetsOutputDto::class, false];
    }

    public function testTransform(): void
    {
        $company = $this->createMock(Company::class);

        $language = 'de';

        $this->i18NService
            ->expects(self::once())
            ->method('getLanguageFromRequest')
            ->willReturn($language)
        ;

        $folder = $this->createMock(Asset\Folder::class);
        $this->folderService
            ->expects(self::once())
            ->method('getOrCreateAssetsFolderForCompany')
            ->with($company, $language)
            ->willReturn($folder)
        ;

        $companyFileCategory = $this->createMock(CompanyFileCategory::class);
        $companyFileCategory->expects(self::once())
        ->method('getId')
        ->willReturn(1337);

        $asset = $this->createMock(Asset::class);

        $assets = new Asset\Listing();
        $assets->setAssets([$asset]);

        $this->assetRepository
            ->expects(self::once())
            ->method('findAllAssetsInFolder')
            ->with($folder)
            ->willReturn( $assets)
        ;


        $asset
            ->expects(self::once())
            ->method('getFullPath')
            ->willReturn('http://sourse.jpg')
        ;

        $asset
            ->expects(self::once())
            ->method('getId')
            ->willReturn(456)
        ;

        $asset
            ->expects(self::exactly(3))
            ->method('getProperty')
            ->withConsecutive(['description'], ['originalFilename'], ['company_asset_category'])
            ->willReturnOnConsecutiveCalls('exampleDescription', 'exampleOriginalFilename.jpg', $companyFileCategory)
        ;

        $response = $this->transformer->transform($company, CompanyAssetsOutputDto::class);
        self::assertSame($language, $response->language);
        self::assertSame('Documents', $response->name);
        self::assertInstanceOf(CompanyAssetDto::class, $response->data[0]);
        self::assertSame(456, $response->data[0]->id);
        self::assertSame('http://sourse.jpg', $response->data[0]->uri);
        self::assertSame('exampleDescription', $response->data[0]->description);
        self::assertSame(1337, $response->data[0]->categoryId);
        self::assertSame('exampleOriginalFilename.jpg', $response->data[0]->originalFilename);
    }
}
