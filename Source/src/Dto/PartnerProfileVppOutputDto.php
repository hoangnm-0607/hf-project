<?php


namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource (
 *     shortName="Studio"
 * )
 */
final class PartnerProfileVppOutputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Land",
     *              "example"="Deutschland",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $country;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Partner Public-ID",
     *              "example"="12345",
     *              "maxLength"=10,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("string")
     */
    public string $publicId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="CAS public ID",
     *              "example"="12345",
     *              "maxLength"=10,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("string")
     */
    public string $casPublicId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Name des Studios",
     *              "example"="Fit-Club",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $studioName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Straße",
     *              "example"="Bahnhofsplatz",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $street;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Hausnummer",
     *              "example"="25 A",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $streetNumber;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="PLZ",
     *              "example"="13365",
     *              "maxLength"=10,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("string")
     */
    public string $zip;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Stadt",
     *              "example"="Buxtehude",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $city;

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
     * @Assert\Length(max="7")
     * @Assert\Type("numeric")
     */
    public float $coordLat;

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
     * @Assert\Length(max="7")
     * @Assert\Type("numeric")
     */
    public float $coordLong;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="E-Mail-Adresse",
     *              "example"="kontakt@fit-club.de",
     *              "maxLength"=100,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="100")
     * @Assert\Type("string")
     */
    public string $email;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Telefonnummer",
     *              "example"="+4966555555",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $phonenumber;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Webseite",
     *              "example"="https://fit-club.fake",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $website;


    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all fitness services with the availability",
     *              "type"="object",
     *              "example"={
     *                  "Kraftbereich": "inclusive",
     *              },
     *              "additionalProperties"={
     *                  "type"="string"
     *              },
     *          }
     *     }
     * )
     * @var array<string,string>
     */
    public array $fitnessServices;

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all wellness services with the availability",
     *              "type"="object",
     *              "example"={
     *                  "Kräutersauna": "inclusive",
     *              },
     *              "additionalProperties"={
     *                  "type"="string"
     *              },
     *          }
     *     }
     * )
     * @var array<string,string>
     */
    public array $wellnessServices;

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all general services with the availability",
     *              "type"="object",
     *              "example"={
     *                  "Personal Training"="inclusive",
     *              },
     *              "additionalProperties"={
     *                  "type"="string"
     *              },
     *          }
     *     }
     * )
     * @var array<string,string>
     */
    public array $services;

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Contains all contract services with the availability",
     *              "type"="object",
     *              "example"={
     *                  "Personal Training"="inclusive",
     *              },
     *              "additionalProperties"={
     *                  "type"="string"
     *              },
     *          }
     *     }
     * )
     * @var array<string,string>
     */
    public array $contractServices;

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
     *              "description"="Teilnahme am Twogether-Programm",
     *              "example"=false,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public bool $twogether = false;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Check-In mit Hansefit-Card",
     *              "example"=true,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public bool $checkInCard = false;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Check-In mit Hansefit-App",
     *              "example"=true,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public bool $checkInApp = false;

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
    public array $checkinInformation;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Ausstellung einer Hansefit-Card",
     *              "example"=true,
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public bool $hansefitCard = false;

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
     *              "description"="Partner-Kategorie ID (Hauptkategorie)",
     *              "example"=24,
     *              "maxLength"=5,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="5")
     * @Assert\Type("integer")
     */
    public int $categoryPrimary;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Weitere Kategorie IDs ",
     *              "example"="[12,44,5]",
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
    public ?array $categories = null;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("array")
     *
     * @var OpeningHoursDto[]
     */
    public array $openingHours = [];


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
    public array $holidays;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Profile completion percentage value",
     *              "example"=24,
     *              "maxLength"=3,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Range(
     *     min = 0,
     *     max = 100,
     * )
     * @Assert\Type("integer")
     */
    public int $completionPercentage;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Termination date reached and so changes are allowed or not",
     *              "example"=false,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Type("boolean")
     */
    public bool $readonly = false;

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
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Has access to course manager",
     *              "example"=false,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Type("boolean")
     */
    public bool $showCourseManager = false;
}
