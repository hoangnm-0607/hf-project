<?php

namespace App\Service;

use Carbon\Carbon;
use Doctrine\DBAL\Exception;
use Pimcore\Db;
use Pimcore\Log\Handler\ApplicationLoggerDb;
use Pimcore\Model\DataObject\ClassDefinition\Layout\DynamicTextLabelInterface;
use Pimcore\Model\DataObject\OnlineProduct;
use Pimcore\Model\DataObject\Voucher;

class VoucherSummaryTextService implements DynamicTextLabelInterface
{

    /**
     * @throws Exception
     */
    public function renderLayoutText($data, $object, $params): string
    {
        /** @var OnlineProduct $object */
        $voucherFolder = $object->getChildren(['folder'])[0];

        $vouchers = $voucherFolder->getChildren(['object']);
        $countAllVouchers = count($vouchers);
        $countAvailableVouchers = 0;
        $countRedeemedVouchers = 0;
        /** @var Voucher $voucher */
        foreach ($vouchers as $voucher) {
            if ($voucher->getRedeemedDateTime()) {
                $countRedeemedVouchers++;
            } else if ($voucher->getExpirationDate() >= Carbon::now()->addDays(28)) {
                $countAvailableVouchers++;
            }

        }

        $logText = ($name = $object->getKey()) ? $this->getLogText($name) : '';

        $text = sprintf('<tr><td>%d </td><td>Gutschein%s gesamt</td></tr>', $countAllVouchers, ($countAllVouchers > 1 || $countAllVouchers == 0 ? 'e' : ''));
        $text .= sprintf('<tr><td>%d </td><td>Gutschein%s verfügbar</td></tr>', $countAvailableVouchers, ($countAvailableVouchers > 1 || $countAvailableVouchers == 0 ? 'e' : ''));
        $text .= sprintf('<tr><td>%d </td><td>Gutschein%s vergeben</td></tr>', $countRedeemedVouchers, ($countRedeemedVouchers > 1 || $countRedeemedVouchers == 0 ? 'e' : ''));
        $table = sprintf('<table class="voucherSummary"><thead><tr><td colspan="2"><strong>Voucher-Bestand</strong></td></tr></thead><tbody>%s</tbody></table>', $text);

        return sprintf('<table class="voucherOuterSummary"><tr><td>%s</td><td>%s</td></tr></table>', $table, $logText);

    }

    /**
     * @throws Exception
     */
    private function getLogText(string $name): string
    {
        $db = Db::getConnection();
        $qb = $db->createQueryBuilder();

        $sql = sprintf(
            'SELECT * FROM %s WHERE `component` = \'DATA-IMPORTER ' . $name . '\' && `message` like %s ORDER BY `timestamp` DESC LIMIT 1',
            ApplicationLoggerDb::TABLE_NAME,
            $db->quote('Loading source data from configured source%')
        );

        if ($xx = $db->fetchAll($sql)) {
            $qb->select('*')
               ->from(ApplicationLoggerDb::TABLE_NAME);

            $qb->andWhere('component = ' . $qb->createNamedParameter('DATA-IMPORTER ' . $name));
            $qb->andWhere('id > ' . $xx[0]['id']);
            $qb->addOrderBy('timestamp', 'DESC');

            $stmt   = $qb->execute();
            $result = $stmt->fetchAll();

            $total = count($result);

            $validationErrors = count(
                array_filter($result, function ($val) {
                    return stripos($val['message'], 'Voucher validation mismatch');
                })
            );
            $importSkipped    = count(
                array_filter($result, function ($val) {
                    return stripos($val['message'], 'Duplicate full path');
                })
            );
            $imported         = count(
                array_filter($result, function ($val) {
                    return stripos($val['message'], 'successfully');
                })
            );

            $table      = '<table class="voucherSummary">';
            $importDate = Carbon::createFromTimeString($xx[0]['timestamp'])->format('d.m.Y - H:i:s');

            $text  = sprintf('<thead><tr><td colspan="2"><strong>Import vom %s</strong></td></tr></thead>', $importDate);
            $text .= sprintf('<tbody><tr><td>%d </td><td>Gutschein%s gesamt in CSV Datei</td></tr>', $total, ($total > 1 || $total == 0 ? 'e' : ''));
            $text .= sprintf('<tr><td>%d </td><td>Gutschein%s nicht valide</td></tr>', $validationErrors, ($validationErrors > 1 || $validationErrors == 0 ? 'e' : ''));
            $text .= sprintf('<tr><td>%d </td><td>Gutschein%s bereits vorhanden</td></tr>', $importSkipped, ($importSkipped > 1 || $importSkipped == 0 ? 'e' : ''));
            $text .= sprintf('<tr><td>%d </td><td>Gutschein%s importiert</td></tr>', $imported, ($imported > 1 || $imported == 0 ? 'e' : ''));

            $table .= $text . '</tbody></table>';

            return $table;
        }

        return '';
    }
}
