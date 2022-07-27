<?php

declare(strict_types=1);

namespace App\DataProvider\Company;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Company;
use App\Helper\ConstHelper;
use App\Repository\Asset\AssetRepository;
use App\Service\EndUser\EndUserBulkUploadService;
use App\Traits\AuthorizationAssertHelperTrait;
use App\Traits\RequestStackTrait;
use Pimcore\Model\Asset;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyEndUserBulkUploadListCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    use AuthorizationAssertHelperTrait;
    use RequestStackTrait;

    private AssetRepository $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $companyId = $this->requestStack->getCurrentRequest()->attributes->get('companyId');

        $this->authorizationAssertHelper->assertUserIsCompanyManagerOrAdmin($companyId);

        $parent = Asset::getByPath(sprintf('%s/%s', EndUserBulkUploadService::TEMP_END_USER_FILE_PATH, $companyId));

        if (!$parent instanceof Asset) {
            throw new NotFoundHttpException(sprintf('Bulk uploads not found for company Id: %s', $companyId));
        }

        return $this->assetRepository->findAllWithParent($parent);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Company::class === $resourceClass && 'get-bulk-upload-list'.ConstHelper::AS_MANAGER === $operationName;
    }
}
