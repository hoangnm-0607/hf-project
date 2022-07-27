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
final class ProductLinksOutputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Playstore url",
     *              "example"="https://play.google.com/store/apps/details?id=de.fitbase.challenge.fitbase",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public ?string $androidLink = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Appstart url",
     *              "example"="https://apps.apple.com/de/app/fitbase-schrittwettbewerb/id1469024188",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public ?string $iOSLink = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="External link the product",
     *              "example"="https://fitbase.de/hansefit-suchtpraevention",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $websiteLink;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="External link to a instruction page",
     *              "example"="https://www.hansefit.de/mitarbeiter/online-angebote/online-how-to-fitbase-praeventionskurse-sucht/",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $instructionsUrl;
}
