<?php


namespace App\Dto\OnlinePlus;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="Online+"
 * )
 */
final class VoucherRedeemInputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product-ID",
     *              "example"="879",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $productId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="CAS hashed User-ID",
     *              "example"="123456789",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $casUserId;
}
