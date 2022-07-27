<?php

namespace App\EventListener;

use Pimcore\Bundle\DataHubBundle\Configuration\Dao;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\DataObject\OnlineProduct;

class ProductsCreationListener
{

    /**
     * @throws \Exception
     */
    public function cloneImportConfig(DataObjectEvent $event): void
    {

        if(($object = $event->getElement()) && $object instanceof OnlineProduct) {

            $name  = $object->getKey();

            // create Voucher Folder
            $voucherFolder = new Folder();
            $voucherFolder->setParent($object);
            $voucherFolder->setKey('Voucher');
            $voucherFolder->save();

            $clone = Dao::getByName('VoucherMain');
            $clone->setName($name);

            $configuration = $clone->getConfiguration();
            $configuration["loaderConfig"]["settings"]["assetPath"] = $object->getVoucherCSV()->getFullPath();
            $configuration["resolverConfig"]["createLocationStrategy"]["settings"]["path"] = $voucherFolder->getFullPath();
            $clone->setConfiguration($configuration);
            $clone->save();


        }

    }

    /**
     * @throws \Exception
     */
    public function createAndSetCSVAsset(DataObjectEvent $event): void
    {

        if(($object = $event->getElement()) && $object instanceof OnlineProduct) {

            $name  = $object->getKey();

            // create Voucher Asset
            $voucherAsset = new Asset();
            $voucherAsset->setParent(Asset\Folder::getByPath('/Voucher Uploads'));
            $voucherAsset->setType('text');
            $voucherAsset->setKey($name.'.csv');
            $voucherAsset->save();

            $object->setVoucherCSV($voucherAsset);
        }
    }

}
