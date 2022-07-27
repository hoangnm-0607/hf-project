<?php

namespace App\Service\OnlinePlus;

use App\Service\EmailNotificationService;
use Carbon\Carbon;
use Exception;
use Pimcore\Model\DataObject\OnlineProduct;
use Pimcore\Model\DataObject\Voucher;
use Pimcore\Model\User;

class ProductService
{
    private EmailNotificationService $notificationService;

    public function __construct(EmailNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @throws Exception
     */
    public function deactivateAndNotifyProduct(OnlineProduct $product) {

        $product->setPublished(false);
        $product->save();

        $notifiableUsers = $this->getNotifiableUsers();

        if($notifiableUsers->count() >= 1 ) {
            $this->notificationService->sendNoAvailableVoucherNotification($notifiableUsers, $product->getKey());
        }

    }

    /**
     * @throws Exception
     */
    public function checkupProductsAndVouchers()
    {
        $productList = new OnlineProduct\Listing();
        foreach ($productList as $product) {
            if(($voucherFolder = $product->getChildren(['folder'])[0])
               && $vouchers    = $voucherFolder->getChildren(['object'])) {

                $skipNextCheck = $this->deactivateProductWithoutRedeemableVouchers($product, $vouchers);

                if ( $skipNextCheck !== 'skip' && !$product->hasProperty('notified_expiringVouchers')) {
                    $this->notifyWhenVouchersSoonExpiring($product, $vouchers);
                }
            }
        }
    }


    /**
     * If a product has no redeemable vouchers left (vouchers that aren't redeemed and whose
     * expiration date is at least 28 days away), deactivate the product and notify users
     *
     * @throws Exception
     */
    private function deactivateProductWithoutRedeemableVouchers(OnlineProduct $product, array $vouchers): ?string
    {
        $redeemableVouchers = array_filter($vouchers, function ($voucher) {
            return ($voucher instanceof Voucher)
                   && !$voucher->getRedeemedDateTime()
                   && ($voucher->getExpirationDate()->greaterThanOrEqualTo(Carbon::now()->addDays(28)));
        });

        if (count($redeemableVouchers) === 0) {
            $this->deactivateAndNotifyProduct($product);
            return 'skip';
        }
        return null;
    }


    /**
     * Checks if a product has minimum one voucher whose expiration date lies at least 35 days in the future and
     * sends a notification of soon to expiring vouchers, otherwise.
     * @throws Exception
     */
    private function notifyWhenVouchersSoonExpiring(OnlineProduct $product, array $vouchers): void
    {
        $notExpiringVouchers = array_filter($vouchers, function ($voucher) {
            if ($voucher instanceof Voucher) {
                return !$voucher->getRedeemedDateTime()
                       && ($voucher->getExpirationDate()->greaterThan(Carbon::now()->addDays(35)));
            }
            return false;
        });

        // all voucher's expiration dates are before today + 35 days, so we'll send a notification
        if (count($notExpiringVouchers) <= 0) {
            $notifiableUsers = $this->getNotifiableUsers();

            if ($notifiableUsers->count() >= 1) {
                $this->notificationService->sendSoonExpiringVoucherNotification($notifiableUsers, $product->getKey());

                // set flag to prevent multiple reminder emails
                $product->setProperty('notified_expiringVouchers', 'bool', true);
                $product->save();
            }
        }
    }


    private function getNotifiableUsers(): User\Listing
    {
        $notifiableUsers = new User\Listing();
        $notifiableUsers->setCondition('admin = 1');

        return $notifiableUsers;
    }

}
