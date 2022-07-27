<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Availability;
use App\Repository\SingleEventRepository;
use Pimcore\Model\DataObject\SingleEvent;

class AvailabilityItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private SingleEventRepository $singleEventRepository;

    public function __construct(SingleEventRepository $singleEventRepository)
    {
        $this->singleEventRepository = $singleEventRepository;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []):? SingleEvent
    {
        $event = $this->singleEventRepository->getOneSingleEventById($id);

        return $event instanceof SingleEvent ? $event : null;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Availability::class;
    }
}
