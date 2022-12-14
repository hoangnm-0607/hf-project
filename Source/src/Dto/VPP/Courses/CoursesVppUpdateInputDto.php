<?php


namespace App\Dto\VPP\Courses;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class CoursesVppUpdateInputDto
{
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
    public ?array $courseName = null;

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
    public ?string $internalName = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Course type",
     *              "example"="Onlinecourse",
     *              "maxLength"=100,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    public ?string $courseType = null;

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
     *                  "de"="Unser Yoga-Kurs f??r Einsteiger.",
     *                  "en"="Our yoga course for amateurs",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public ?array $shortDescription = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Level, if empty Anf??nger and Fortgeschrittene will be chosen",
     *              "example"="Anf??nger",
     *              "maxLength"=16,
     *              "type"="string",
     *              "enum"={"","Anf??nger","Fortgeschritten"}
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
     *              "description"="Needed Accessoires",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Yogamatte, evt. Yogabl??cke",
     *                  "en"="Yoga stuff",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public ?array $neededAccessoires = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Course duration",
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
     *              "description"="Course instructor",
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
     *              "description"="Main category (ID)",
     *              "example"=181,
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
     *              "description"="Additional categories (IDs) ",
     *              "example"="[182]",
     *              "maxLength"=5,
     *              "type"="array",
     *              "items"= {"type"="integer"}
     *         }
     *     }
     * )
     * @Assert\Type("array")
     *
     * @var int[]|null
     */
    public ?array $secondaryCategories = null;

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
    public ?bool $published = null;
}
