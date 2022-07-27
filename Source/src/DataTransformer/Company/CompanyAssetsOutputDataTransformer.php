<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyAssetDto;
use App\Dto\Company\CompanyAssetsOutputDto;
use App\Dto\Company\CompanyDataAssetDto;
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
        $dataAssets = $this->assetRepository->findAllWithParent($companyDocumentsFolder);

        foreach ($dataAssets as $dataAsset) {
            $assets = $this->assetRepository->findAllWithParent($dataAsset);

            $dataAssetDto = new CompanyDataAssetDto();
            $dataAssetDto->value = $dataAsset->getKey();
            $dataAssetDto->id = $dataAsset->getId();
            $dataAssetDto->type = 'folder';

            foreach ($assets as $asset) {
                $assetDto = new CompanyAssetDto();
                $assetDto->id = $asset->getId();
                $assetDto->type = 's3';
                $assetDto->value = $asset->getFullPath();

                $dataAssetDto->data[] = $assetDto;
            }

            $target->data[] = $dataAssetDto;
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceOf Company && CompanyAssetsOutputDto::class === $to;
    }
}
