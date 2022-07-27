<?php


namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ApiResource (
 *     shortName="Studio update"
 * )
 */
final class PartnerProfileVppInputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Name of the studio",
     *              "example"="Fit-Club",
     *              "maxLength"=255,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="255")
     * @Assert\Type("string")
     */
    public ?string $studioName = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Coordinates Lat",
     *              "example"="53.5661",
     *              "maxLength"=7,
     *              "type"="number"
     *         }
     *     }
     * )
     * @Assert\Type("numeric")
     * @Assert\GreaterThan(value=-90)
     * @Assert\LessThanOrEqual(value=90)
     */
    public ?float $coordLat = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Coordinates Long",
     *              "example"="53.5661",
     *              "maxLength"=7,
     *              "type"="number"
     *         }
     *     }
     * )
     * @Assert\Type("numeric")
     * @Assert\GreaterThan(value=-180)
     * @Assert\LessThanOrEqual(value=180)
     */
    public ?float $coordLong = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="E-Mail-Address",
     *              "example"="kontakt@fit-club.de",
     *              "maxLength"=100,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    public ?string $email = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Phone number",
     *              "example"="+4966555555",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $phonenumber = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Website",
     *              "example"="https://fit-club.fake",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $website = null;

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all services with the availability",
     *              "type"="object",
     *              "example" = {"Kraftbereich"="inclusive"}
     *          }
     *     }
     * )
     * @var array|null
     */
    public ?array $fitnessServices = null;

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all services with the availability",
     *              "type"="object",
     *              "example" = {"Kräutersauna"="inclusive"}
     *          }
     *     }
     * )
     * @var array|null
     */
    public ?array $wellnessServices = null;

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all services with the availability",
     *              "type"="object",
     *              "example" = {"Personal Training"="inclusive"}
     *          }
     *     }
     * )
     * @var array|null
     */
    public ?array $services = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Description of the studio",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Frisch renovierte Studio mit Sauna",
     *                  "en"="Newly renovated studio with sauna",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public ?array $description = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Notes",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Testzentrum im Eingang",
     *                  "en"="Test center in the entrance",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public ?array $notes = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Twogether-Programm participation",
     *              "example"=false,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $twogether = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Check-In with Hansefit-Card",
     *              "example"=true,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $checkInCard = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Check-In with Hansefit-App",
     *              "example"=true,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $checkInApp = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Additional check-in informations",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Beim Empfang anmelden",
     *                  "en"="Register at reception",
     *              },
     *              "maxLength"=10000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public ?array $checkinInformation = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Able to issue a Hansefit-Card",
     *              "example"=true,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $hansefitCard = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Tags (CSV)",
     *              "example"="Citynah,Kinderfreundlich,Wasserspender",
     *              "maxLength"=100,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    public ?string $tags = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Partner-Category ID (Maincategory)",
     *              "example"=24,
     *              "maxLength"=5,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="5")
     * @Assert\Type("integer")
     */
    public ?int $categoryPrimary = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Additional Category IDs ",
     *              "example"="[12,44,5]",
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
    public ?array $categories = null;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("array")
     *
     * @var OpeningHoursDto[]
     */
    public ?array $openingHours = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Description of the studio",
     *              "properties"={
     *                      "en"={"type"="string"},
     *                      "de"={"type"="string"}
     *              },
     *              "example"={
     *                  "de"="Jeden zweiten Sonntag geschlossen, Heiligabend geöffnet",
     *                  "en"="Closed each second Sunday, christmas eve open",
     *              },
     *              "maxLength"=1000,
     *              "type"="object"
     *         }
     *     },
     * )
     * @Assert\Type("array")
     */
    public ?array $holidays = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Publish der opening times",
     *              "example"=false,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Type("boolean")
     */
    public bool $showOpeningTimes = true;

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->openingHours != null) {
            if (sizeof($this->openingHours) != 7) {
                return $context->buildViolation('openingHours need to contains 7 elements, one for each weekday')
                    ->atPath('openingHours')
                    ->addViolation();
            }
            foreach ($this->openingHours as $dayInformation) {
                if (!isset($dayInformation->weekday) || !in_array($dayInformation->weekday, OpeningHoursDto::WEEKDAYS)) {
                    return $context->buildViolation('Invalid or missing weekday element')
                        ->atPath('openingHours')
                        ->addViolation();
                }
                foreach ($dayInformation->times as $timeInterval) {
                    if (!isset($timeInterval["from"]) || !isset($timeInterval["to"])) {
                        return $context->buildViolation('Missing from or to element')
                            ->atPath('openingHours')
                            ->addViolation();
                    }
                    if ($timeInterval["from"] && !preg_match("/^(?:(?:2[0-3]|[01]\d):[0-5]\d|24:00)$/", $timeInterval["from"])) {
                        return $context->buildViolation('Invalid value for from with value: {{ value }}')
                            ->atPath('openingHours')
                            ->setParameter('{{ value }}', $timeInterval["from"])
                            ->addViolation();
                    }
                    if ($timeInterval["to"] && !preg_match("/^(?:(?:2[0-3]|[01]\d):[0-5]\d|24:00)$/", $timeInterval["to"])) {
                        return $context->buildViolation('Invalid value for to with value: {{ value }}')
                            ->atPath('openingHours')
                            ->setParameter('{{ value }}', $timeInterval["to"])
                            ->addViolation();
                    }
                }
            }
        }
    }
}
