<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Company;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataTransformer\Company\CompanyAssetDocumentInputDataTransformer;
use App\Dto\Company\CompanyAssetDocumentInputDto;
use App\Entity\Company;
use App\Service\FolderService;
use App\Service\I18NService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CompanyAssetDocumentInputDataTransformerTest extends TestCase
{
    /** @var FolderService|MockObject */
    private FolderService|MockObject $folderService;

    /** @var ValidatorInterface|MockObject */
    private ValidatorInterface|MockObject $validator;

    /** @var I18NService|MockObject */
    private I18NService|MockObject $i18NService;

    private CompanyAssetDocumentInputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->i18NService = $this->createMock(I18NService::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->folderService = $this->createMock(FolderService::class);

        $this->transformer = new CompanyAssetDocumentInputDataTransformer($this->folderService);
        $this->transformer->setI18NService($this->i18NService);
        $this->transformer->setValidator($this->validator);
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
            $this->folderService,
            $this->validator,
            $this->i18NService,
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
        yield [$this->createMock(CompanyAssetDocumentInputDto::class), Company::class, true];
        yield [$this->createMock(CompanyAssetDocumentInputDto::class), \stdClass::class, false];
        yield [null, Company::class, false];
    }
}
