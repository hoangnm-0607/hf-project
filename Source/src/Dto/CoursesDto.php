<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class CoursesDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kurs ID",
     *              "example"="33445",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $courseId = '';

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kursname",
     *              "example"="Yoga für Einsteiger",
     *              "maxLength"=500,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="500")
     * @Assert\Type("string")
     */
    public string $courseName = '';

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kurstyp",
     *              "example"="Onlinecourse",
     *              "maxLength"=100,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    public string $courseType = '';

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kursbeschreibung",
     *              "example"="Unser Yoga-Kurs für Einsteiger.",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public string $shortDescription = '';

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kursfoto",
     *              "example"="https://link/zum/kursfoto.jpg",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $courseImage = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Level",
     *              "example"="Anfänger",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public string $level = '';

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Benötigte Hilfsmittel",
     *              "example"="Yogamatte, evt. Yogablöcke",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $neededAccessoires = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kursdauer",
     *              "example"="90",
     *              "maxLength"=3,
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Length(max="3")
     * @Assert\Type("integer")
     */
    public ?int $courseDuration = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Kursleiter",
     *              "example"="Yogi B.",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $courseInstructor = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Exklusivkurs",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("boolean")
     */
    public bool $exclusiveCourse = false;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Paket-ID",
     *              "example"="30",
     *              "type"="integer",
     *              "enum"={30,31}
     *         }
     *     },
     * )
     * @Assert\Length(max="2")
     * @Assert\Type("integer")
     */
    public int $packageId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Hauptkurskategorie (ID)",
     *              "example"=65,
     *              "maxLength"=5,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="5")
     * @Assert\Type("integer")
     */
    public int $mainCategory = 0;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Weitere Kategorien (IDs) ",
     *              "example"="[22,12,83]",
     *              "maxLength"=5,
     *              "type"="array",
     *              "items"= {"type"="integer"}
     *         }
     *     }
     * )
     * @Assert\Length(max="5")
     * @Assert\Type("array")
     *
     * @var int[]
     */
    public ?array $secondaryCategories = null;


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="CAS Check-In (App) ",
     *              "example"="987654321bbbbb",
     *              "maxLength"=40,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="40")
     * @Assert\Type("string")
     *
     */
    public string $casConfigCheckInApp = '';


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Verbundpartner ID",
     *              "example"="56666",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $partnerId = '';


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Name des Studios",
     *              "example"="Globo-Gym",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $partnerName = '';

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Beschreibung des Studios",
     *              "example"="Besser als Average Joe's. Heimat der Purple Cobras.",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $partnerDescription = '';

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("array")
     *
     * @var EventDto[]
     */
    public array $events = [];

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Published",
     *              "example"="false",
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Type("boolean")
     */
    public bool $published = true;
}
