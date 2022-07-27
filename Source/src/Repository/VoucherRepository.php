<?php

namespace App\Repository;

use App\Entity\OnlineProduct;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Pimcore\Model\DataObject\Voucher\Listing;

class VoucherRepository
{
    public function getExpiredVouchers(): Listing
    {
        $listing = new Listing();
        $listing->setCondition('RedeemedDateTime IS NULL AND ExpirationDate <= ?', Carbon::now()->subDays(10));

        return $listing;
    }

    public function getArchivableVouchers(): Listing
    {
        $listing = new Listing();
        $listing->setCondition('RedeemedDateTime <= ? AND o_path NOT LIKE \'%Archive%\'', Carbon::now()->subDays(28));

        return $listing;
    }

    public function getActiveVoucherForCasUserId(OnlineProduct $product, string $casUserId): Listing {
        $activeVouchers = new Listing;
        $activeVouchers->setCondition(
            'object_Voucher.o_path = ? AND ExpirationDate > now() AND now() BETWEEN RedeemedDateTime AND DATE_ADD(RedeemedDateTime, INTERVAL 28 DAY)',
            [$product->getRealFullPath() . '/Voucher/', $casUserId]
        );
        $activeVouchers->onCreateQueryBuilder(
            function (QueryBuilder $queryBuilder) {
                $queryBuilder->join(
                    'object_Voucher',
                    'object_courseuser',
                    'user',
                    'object_Voucher.RedeemedUser__id = user.oo_id'
                );
                $queryBuilder->andWhere('user.UserId = ?');
            }
        );
        $activeVouchers->setLimit(1);

        return $activeVouchers;
    }

    public function getRedeemableVoucher(OnlineProduct $product): Listing {
        $newVoucher = new Listing;
        $newVoucher->setCondition(
            'object_Voucher.o_path = ? AND RedeemedUser__id IS NULL AND ExpirationDate > DATE_ADD(now(), INTERVAL 28 DAY)',
            [$product->getRealFullPath() . '/Voucher/']
        );
        $newVoucher->setLimit(1);

        return $newVoucher;
    }

}
