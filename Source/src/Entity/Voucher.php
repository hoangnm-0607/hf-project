<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\OnlinePlus\VoucherRedeemInputDto;
use App\Dto\OnlinePlus\VoucherRedeemOutputDto;
use Pimcore\Model\DataObject\Voucher as DataObjectVoucher;

/**
 * @ApiResource(
 *  attributes={
 *      "pagination_enabled"=false
 *     },
 *  shortName="Online+",
 *  normalizationContext={
 *      "allow_extra_attributes"=false,
 *      "skip_null_values" = false,
 *  },
 *  formats={"json"},
 *  collectionOperations={
 *     "post_redeem_voucher"={
 *          "method"="POST",
 *          "path"="/coupon/redeemcode",
 *          "input"=VoucherRedeemInputDto::class,
 *          "output"=VoucherRedeemOutputDto::class,
 *          "openapi_context"={
 *              "summary"="Redeems a code for the given user",
 *              "description"="Redeems a code for the given user",
 *          },
 *      },
 *  },
 *  itemOperations={}
 * )
 */

final class Voucher extends DataObjectVoucher
{
    /**
     * @ApiProperty(identifier=true)
     */
    protected ?int $voucherId;

    public function getVoucherId(): ?int
    {
        return $this->voucherId;
    }

}
