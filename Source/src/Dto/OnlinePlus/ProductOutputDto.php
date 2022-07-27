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
final class ProductOutputDto
{

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product-ID",
     *              "example"="64",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $productId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="CAS Product-ID",
     *              "example"="61",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $casProductId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Original price of this product",
     *              "example"="12.34",
     *              "type"="float"
     *         }
     *     },
     * )
     * @Assert\Type("float")
     */
    public float $originalPrice;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product name",
     *              "example"="FITBASE SUCHTPRÄVENTION",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $name;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product description",
     *              "example"="Die zertifizierten Präventionskurse von fitbase aus dem Bereich...",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $description;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product small description",
     *              "example"="Du erhältst einen 100% Gutscheincode, den Du für den Nichtraucher-Kurs von Fitbase einlösen kannst...",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $smallDescription;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product title",
     *              "example"="Fitbase Suchtprävention",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $title;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Product category",
     *              "example"="Fitness",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $productCategory;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Sub product title",
     *              "example"="MIT DEM RAUCHEN AUFHÖREN",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $subTitle;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Content teaser",
     *              "example"="Der zertifizierte Online-Präventinskurse zum Thema Suchtprävention unterstützt Dich dabei, mit dem Rauchen aufzuhören.",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $contentTeaser;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number days the product is still valid",
     *              "example"="28",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $validityInDays;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Backgorund color in hex",
     *              "example"="046E95",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $backgroundHex;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Url to the icon",
     *              "example"="https://hansecoreapi-staging.azurewebsites.net/api/Product/9/icon.png",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Type("string")
     */
    public string $iconUrl;


    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\OnlinePlus\ProductCodeOutputDto")
     *
     * @var ?ProductCodeOutputDto
     */
    public ?ProductCodeOutputDto $productCodeDto = null;


    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\OnlinePlus\ProductLinksOutputDto")
     *
     * @var ProductLinksOutputDto
     */
    public ProductLinksOutputDto $productLinksDto;

}
