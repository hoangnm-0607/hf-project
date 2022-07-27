<?php


namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="assets output"
 * )
 */
final class AssetsVppOutputDto
{
    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Assets\LogoOutputDto")
     *
     * @var LogoOutputDto
     */
    public LogoOutputDto $logo;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Assets\GalleryOutputDto")
     *
     * @var ?GalleryOutputDto
     */
    public array $gallery = [];

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Assets\VideoDto")
     *
     * @var ?VideoDto
     */
    public ?VideoDto $video = null;
}
