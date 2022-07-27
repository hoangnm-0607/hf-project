<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\PartnerProfile;
use App\Exception\ObjectNotFoundException;
use App\Service\PartnerProfileService;
use Exception;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\RequestStack;

class AssetsVppDeleteDataPersister implements ContextAwareDataPersisterInterface
{
    private RequestStack $requestStack;
    private PartnerProfileService $partnerProfileService;

    public function __construct(RequestStack $requestStack, PartnerProfileService $partnerProfileService)
    {
        $this->requestStack = $requestStack;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @inheritDoc
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof PartnerProfile &&
            isset($context['item_operation_name']) && $context['item_operation_name'] == 'delete_asset';
    }

    /**
     * @inheritDoc
     */
    public function persist($data, array $context = [])
    {

    }

    /**
     * @inheritDoc
     * @param PartnerProfile $data
     * @throws Exception
     */
    public function remove($data, array $context = [])
    {
        $this->partnerProfileService->checkIfChangesAreAllowed($data);

        $assetId = $this->requestStack->getCurrentRequest()->attributes->get('assetId');
        $asset = Asset::getById($assetId);
        if ($asset == null) {
            throw new ObjectNotFoundException("Asset with Id " . $assetId . " not found");
        }

        if ($asset instanceof Asset\Video) {
            $videoData = $data->getStudioVideo()?->getData();
            if ($videoData instanceof Asset\Video && $videoData->getId() == $assetId) {
                $data->getStudioVideo()->setData(null);
                $data->save();
            }
        }

        $asset->delete();
    }
}
