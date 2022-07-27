<?php


namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="assets logo output"
 * )
 */
final class LogoOutputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Resource URI",
     *              "example"="abc.jpg",
     *              "type"="string",
     *         }
     *     }
     * )
     * @Assert\Type("string")
     */
    public ?string $uri = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Pimcore assetId",
     *              "example"=1234,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Type("integer")
     */
    public ?int $assetId = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Original Filename",
     *              "example"="real_abc.jpg",
     *              "type"="string",
     *         }
     *     }
     * )
     * @Assert\Type("string")
     */
    public ?string $originalFilename = null;
}
