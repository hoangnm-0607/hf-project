<?php

namespace App\EventListener;

use Exception;
use Pimcore\Bundle\DataImporterBundle\Processing\ImportPreparationService;
use Pimcore\Bundle\DataImporterBundle\Processing\ImportProcessingService;
use Pimcore\Bundle\DataImporterBundle\Queue\QueueService;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Model\DataObject\OnlineProduct;

class VoucherAssetUpdateListener
{
    private ImportPreparationService $importPreparationService;
    private QueueService $queueService;
    private ImportProcessingService $importProcessingService;

    public function __construct(
        ImportPreparationService $importPreparationService,
        QueueService $queueService,
        ImportProcessingService $importProcessingService
    )
    {
        $this->importPreparationService = $importPreparationService;
        $this->queueService = $queueService;
        $this->importProcessingService = $importProcessingService;
    }

    /**
     * @throws \Pimcore\Bundle\DataImporterBundle\Exception\InvalidConfigurationException
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    public function triggerImportWhenAssetUpdated(AssetEvent $event)
    {
        if (($asset = $event->getAsset()) && $asset->getPath() === '/Voucher Uploads/') {
            $configName = str_replace('.csv', '', $asset->getKey());
            $this->tryToResetNotificationProperty($configName);
            $this->importPreparationService->prepareImport($configName);
            $ids = $this->queueService->getAllQueueEntryIds(ImportProcessingService::EXECUTION_TYPE_SEQUENTIAL);
            foreach ($ids as $id) {
                $this->importProcessingService->processQueueItem($id);
            }
        }

    }

    /**
     * @throws Exception
     */
    private function tryToResetNotificationProperty(string $productName)
    {
        $product = OnlineProduct::getByPath('/Online+/Products/'.$productName);
        $product->removeProperty('notified_expiringVouchers');

        $product->save();
    }

}
