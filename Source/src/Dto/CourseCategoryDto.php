<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class CourseCategoryDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="ID",
     *              "example"="151",
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
     *              "description"="Name/Kurzbeschreibung",
     *              "example"="Pilates",
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
     *              "example"="Pilates, auch Pilates-Methode genannt, ist ein systematisches Ganzkörpertraining zur Kräftigung der Muskulatur",
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
     *              "example"="https://link/zum/kategorieiconFilled.jpg",
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
     *              "description"="Kategoriebild als Fallback, falls kein Kursbild geflegt wurde",
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
