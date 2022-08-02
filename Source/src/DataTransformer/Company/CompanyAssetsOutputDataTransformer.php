<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyAssetDto;
use App\Dto\Company\CompanyAssetsOutputDto;
use App\Entity\Company;
use App\Repository\Asset\AssetRepository;
use App\Service\FolderService;
use App\Traits\I18NServiceTrait;

class CompanyAssetsOutputDataTransformer implements DataTransformerInterface
{
    use I18NServiceTrait;

    private FolderService $folderService;
    private AssetRepository $assetRepository;

    public function __construct(FolderService $folderService, AssetRepository $assetRepository)
    {
        $this->folderService = $folderService;
        $this->assetRepository = $assetRepository;
    }

    /**
     * @param Company $object
     * @param string  $to
     * @param array   $context
     *
     * @return CompanyAssetsOutputDto
     *
     * @throws \Exception
     */
    public function transform($object, string $to, array $context = []): CompanyAssetsOutputDto
    {
        $language = $this->i18NService->getLanguageFromRequest();

        $target = new CompanyAssetsOutputDto();
        $target->language = $language;
        $target->name = FolderService::DOCUMENTS;

        $companyDocumentsFolder = $this->folderService->getOrCreateAssetsFolderForCompany($object, $language);

        $assets = $this->assetRepository->findAllAssetsInFolder($companyDocumentsFolder);

        foreach ($assets as $asset) {
            $assetDto = new CompanyAssetDto();
            $assetDto->id = $asset->getId();
            $assetDto->uri = $asset->getFullPath();
            $assetDto->description = $asset->getProperty('description');
            $assetDto->originalFilename = $asset->getProperty('originalFilename');
            $assetDto->categoryId = $asset->getProperty('company_asset_category')->getId();

            $target->data[] = $assetDto;
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceOf Company && CompanyAssetsOutputDto::class === $to;
    }
}
