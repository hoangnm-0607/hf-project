<?php

namespace App\Command;

use App\Repository\VoucherRepository;
use App\Service\ArchiveService;
use App\Service\OnlinePlus\ProductService;
use Exception;
use Pimcore\Log\Simple;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VoucherCleanupCommand extends AbstractCommand
{
    private VoucherRepository $voucherRepository;
    private ArchiveService $archiveService;
    private ProductService $productService;

    public function __construct(VoucherRepository $voucherRepository, ArchiveService $archiveService, ProductService $productService, string $name = null)
    {
        parent::__construct($name);
        $this->voucherRepository = $voucherRepository;
        $this->archiveService = $archiveService;
        $this->productService = $productService;
    }

    public function configure(): void
    {
        $this
            ->setName('hanse:cleanup:voucher')
            ->setDescription('Cleans and archives voucher objects')
            ->setHelp('This job deletes voucher objects 10 days after expiration date and archives redeemed voucher objects 28 days after redemption date.');
    }

    /**
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($expiredList = $this->voucherRepository->getExpiredVouchers()) {
            foreach ($expiredList as $voucher) {
                try {
                    $id = $voucher->getId();
                    $voucher->delete();
                    Simple::log('cleanuplog', sprintf('Deleted voucher #%d', $id));
                } catch (Exception $e) {
                    Simple::log('cleanuplog', $e->getMessage());
                }
            }
        }

        $this->productService->checkupProductsAndVouchers();

        if ($archivableList = $this->voucherRepository->getArchivableVouchers()) {
            $this->archiveService->archiveVouchers($archivableList);
        }

        return 0;
    }

}
