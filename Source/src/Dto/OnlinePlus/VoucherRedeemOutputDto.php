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
final class VoucherRedeemOutputDto
{

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

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="CAS public id",
     *              "example"="ab45462hbad",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $casPublicId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="CAS template key",
     *              "example"="abc12345",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $templateKeyCas;
}
