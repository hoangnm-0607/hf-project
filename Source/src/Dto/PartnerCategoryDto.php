<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class PartnerCategoryDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="ID",
     *              "example"="244",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $id;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kurzbeschreibung/Name",
     *              "example"="Tanzstudio",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public string $shortDescription;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Langbeschreibung",
     *              "example"="Tazstudio mir drei Tanzsälen",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $longDescription = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kontur Kategorie-Icon",
     *              "example"="https://link/zum/kategorieicon.jpg",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $iconUrlContour = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Ausgefülltes Kategorie-Icon aktiv",
     *              "example"="https://link/zum/kategorieiconfilled.jpg",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $iconUrlFilled = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kategoriebild als Fallback, falls kein Partnerbild geflegt wurde",
     *              "example"="https://link/zum/kategoriebild.jpg",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $imageUrl = null;
}
