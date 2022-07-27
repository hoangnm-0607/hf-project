<?php


namespace App\Dto\VPP\Courses;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class CoursesVppOutputDto
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
     *              "description"="Course name",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Ein neuer Kurs",
     *                  "en"="A new course",
     *              },
     *              "maxLength"=500,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public array $courseName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Internal Course name (Course key)",
     *              "example"="Ein neuer kurs",
     *              "maxLength"=500,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="500")
     * @Assert\Type("string")
     */
    public string $internalName;

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
     *              "description"="Course description",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Unser Yoga-Kurs für Einsteiger.",
     *                  "en"="Our yoga course for amateurs",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public array $shortDescription;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Needed Accessoires",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Yogamatte, evt. Yogablöcke",
     *                  "en"="Yoga stuff",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public array $neededAccessoires;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Level, if empty Anfänger and Fortgeschrittene is internaly used",
     *              "example"="Anfänger",
     *              "maxLength"=16,
     *              "type"="string",
     *              "enum"={"","Anfänger","Fortgeschritten"}
     *         }
     *     },
     * )
     * @Assert\Length(max="16")
     * @Assert\Type("string")
     */
    public ?string $level = null;

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
     * @Assert\Type("boolean")
     */
    public bool $exclusiveCourse = false;

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
     * @Assert\Type("integer")
     */
    public ?int $mainCategory = null;

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
     * @Assert\Type("array")
     *
     * @var int[]
     */
    public ?array $secondaryCategories = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Course capacity",
     *              "example"="90",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public ?int $capacity = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Date of the first event (YYYY-MM-DD)",
     *              "example"="2021-12-01",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Date
     */
    public ?string $startDate = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Date of the last event (YYYY-MM-DD)",
     *              "example"="2021-12-31",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Date
     */
    public ?string $endDate = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Date of the next event (YYYY-MM-DD)",
     *              "example"="2021-12-31",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Date
     */
    public ?string $nextEventDate = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of published events in the future",
     *              "example"="90",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public ?int $openEvents = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of already performed events",
     *              "example"="90",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public ?int $performedEvents = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of total published and unpublished events",
     *              "example"="100",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $totalEvents;

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
    public bool $published;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Archived",
     *              "example"="false",
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Type("boolean")
     */
    public ?bool $archived = null;
}
