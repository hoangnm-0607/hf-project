<?php


namespace App\Dto\VPP\Assets;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="Asset gallery update"
 * )
 */
 class GalleryUpdateDto
{
     /**
      * @Assert\All({
      *     @Assert\Type("App\Dto\VPP\Assets\GalleryInputDto")
      * })
      * @ApiProperty(readableLink=true)
      *
      * @var GalleryInputDto[]
      */

    public ?array $gallery = null;

}
