<?php

namespace App\EventListener;

use Carbon\Carbon;
use Exception;
use Pimcore\Bundle\DataImporterBundle\Event\DataObject\PreSaveEvent;
use Pimcore\Model\DataObject\Voucher;
use Pimcore\Model\Element\ValidationException;

class VoucherCreationListener
{

    /**
     * @throws Exception
     */
    public function validateVoucherData(PreSaveEvent $event): void
    {
        if (($voucher = $event->getDataObject()) && $voucher instanceof Voucher) {
            $voucherCode = $voucher->getVoucherCode();
            $expirationDate = $voucher->getExpirationDate();
            if(!preg_match('/^.{6,25}$/', $voucherCode) || $expirationDate < Carbon::now()->addDays(28)) {
                throw new ValidationException('Voucher validation mismatch');
            }
        }

    }

}
