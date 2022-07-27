<?php

declare(strict_types=1);

namespace App\DataTransformer\Company;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Company\CompanyAssetDocumentInputDto;
use App\Entity\Company;
use App\Service\FolderService;
use App\Traits\I18NServiceTrait;
use App\Traits\ValidatorTrait;
use Pimcore\Model\Asset;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CompanyAssetDocumentInputDataTransformer implements DataTransformerInterface
{
    use I18NServiceTrait;
    use ValidatorTrait;

    private FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @param CompanyAssetDocumentInputDto $object
     * @param string                       $to
     * @param array                        $context
     *
     * @return Company
     *
     * @throws \Exception
     */
    public function transform($object, string $to, array $context = []): Company
    {
        $this->validator->validate($object);

        /** @var Company $company */
        $company = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $companyDocumentsFolder = $this->folderService->getOrCreateAssetsFolderForCompany($company, $object->language, $object->folderName);

        $asset = new Asset();
        $asset->setParent($companyDocumentsFolder);
        $asset->setFilename($object->filename);

        $asset->setProperty('originalFilename', 'image', $object->originalFilename ?? $object->filename);

        $asset->save(['versionNote' => 'API upload']);

        return $company;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof CompanyAssetDocumentInputDto && Company::class === $to;
    }
}
