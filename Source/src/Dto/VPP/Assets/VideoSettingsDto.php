<?php


namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="assets video settings"
 * )
 */
final class VideoSettingsDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Video of the video: youtube, vimeo, asset",
     *              "example"="asset",
     *              "maxLength"=30,
     *              "type"="string",
     *              "enum"={"youtube","vimeo","asset"}
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public ?string $type = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Filename or videoId. Needs to match the given type",
     *              "example"="SampleVideo.mp4",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $uri = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Filename",
     *              "example"="studioimage.png",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $previewUri = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Asset Id which should be assigned/unassigned",
     *              "example"=123,
     *              "type"="integer",
     *         }
     *     }
     * )
     * @Assert\Type("integer")
     */
    public ?string $previewAssetId = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Asset Id which should be assigned/unassigned",
     *              "example"=123,
     *              "type"="integer",
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
