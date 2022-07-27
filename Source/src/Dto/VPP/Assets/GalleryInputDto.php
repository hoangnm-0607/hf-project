<?php


namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="assets gallery input"
 * )
 */
final class GalleryInputDto
{
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
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Assets\TitleDto")
     *
     * @var ?TitleDto
     */
    public ?TitleDto $title = null;
}
