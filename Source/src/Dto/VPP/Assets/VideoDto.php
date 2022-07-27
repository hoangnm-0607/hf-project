<?php


namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="assets video"
 * )
 */
final class VideoDto
{
    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Assets\VideoSettingsDto")
     *
     * @var ?VideoSettingsDto
     */
    public ?VideoSettingsDto $videoSettings = null;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Assets\TitleDto")
     *
     * @var ?TitleDto
     */
    public ?TitleDto $title = null;
}
