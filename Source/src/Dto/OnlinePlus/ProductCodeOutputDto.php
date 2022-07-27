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
final class ProductCodeOutputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Voucher-ID",
     *              "example"="12344",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="The code",
     *              "example"="49221-1030",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $code;
}
