<?php


namespace App\Dto\VPP\Partners;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="Studio"
 * )
 */
final class PartnerNameDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Partner Public-ID",
     *              "example"="12345",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $publicId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Name des Studios",
     *              "example"="Fit-Club",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $studioName;
}
