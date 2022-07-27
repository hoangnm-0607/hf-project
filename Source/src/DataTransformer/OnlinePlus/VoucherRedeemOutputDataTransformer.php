<?php

namespace App\DataTransformer\OnlinePlus;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\OnlinePlus\VoucherRedeemOutputDto;
use App\Service\DataObjectService;
use Exception;
use Pimcore\Model\DataObject\OnlineProduct;
use Pimcore\Model\DataObject\Voucher;

class VoucherRedeemOutputDataTransformer implements DataTransformerInterface
{

    private DataObjectService $dataObjectService;

    public function __construct(DataObjectService $dataObjectService)
    {
        $this->dataObjectService = $dataObjectService;
    }


    /**
     * @param Voucher $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : VoucherRedeemOutputDto
    {
        $target = new VoucherRedeemOutputDto();
        $target->code = $object->getVoucherCode();

        /** @var OnlineProduct $product */
        $product = $this->dataObjectService->getRecursiveParent($object, OnlineProduct::class);

        $target->casPublicId = $product->getCasPublicId();
        $target->templateKeyCas = $product->getTemplateKey();

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return VoucherRedeemOutputDto::class === $to && $data instanceof Voucher;
    }
}
