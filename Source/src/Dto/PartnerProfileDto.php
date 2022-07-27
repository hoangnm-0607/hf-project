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
final class PartnerProfileDto
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
     *              "description"="Koordinaten Lat",
     *              "example"="53.5661",
     *              "maxLength"=7,
     *              "type"="number"
     *         }
     *     }
     * )
     * @Assert\Length(max="7")
     * @Assert\Type("numeric")
     */
    public ?float $coordLat;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Koordinaten Long",
     *              "example"="53.5661",
     *              "maxLength"=7,
     *              "type"="number"
     *         }
     *     }
     * )
     * @Assert\Length(max="7")
     * @Assert\Type("numeric")
     */
    public ?float $coordLong;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Visible on the map",
     *              "example"=true,
     *              "type"="boolean"
     *         }
     *     }
     * )
     * @Assert\Type("boolean")
     */
    public bool $studioVisibility = true;

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
     *                  "Kräutersauna": "surcharge",
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
     *         "openapi_context"={
     *              "description"="Beschreibung des Studios",
     *              "example"="Wir freuen uns auf euren Besuch",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $description = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Notizen / Allgemeine Informationen",
     *              "example"="Parkplätze vorhanden",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $notes = null;


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Studio-Logo (Link)",
     *              "example"="https://url/zum/logo.jpg",
     *              "maxLength"=200,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="200")
     * @Assert\Type("string")
     */
    public ?string $logo = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Service packages of the studio",
     *              "example"={
     *                  "18"="Twogether Trainingsprogramm",
     *                  "33"="Fitness (Basic)",
     *              },
     *              "type"="object",
     *              "additionalProperties"={
     *                  "type"="string"
     *              },
     *         }
     *     },
     * )
     * @Assert\Type("array")
     * @var array<string,string>
     */
    public array $servicePackages = [];

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Studio-Image",
     *              "type" = "object",
     *                  "properties" = {
     *                          "url" = {
     *                              "type" = "string",
     *                              "example" = "https://url/zum/studiobild.jpg",
     *                              "description" = "Öffentlich erreichbare URL des Bildes",
     *                              "nullable"=true
     *                          },
     *                          "title" = {
     *                              "type" = "string",
     *                              "example" = "Unser tolles Studio",
     *                              "description" = "Bildunterschrift",
     *                              "nullable"=true
     *                          }
     *                    },
     *                  "additionalProperties"=false
     *         }
     *     }
     * )
     * @var array<string,string>|null
     */
    public ?array $studioImage = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Studio-Bilder (Galerie)",
     *              "type"="array",
     *              "items" = {
     *                  "type" = "object",
     *                  "properties" = {
     *                          "url" = {
     *                              "type" = "string",
     *                              "example" = "https://url/zum/studiobild.jpg",
     *                              "description" = "Öffentlich erreichbare URL des Bildes",
     *                              "nullable"=true
     *                          },
     *                          "title" = {
     *                              "type" = "string",
     *                              "example" = "Unsere Lounge-Ecke",
     *                              "description" = "Bildunterschrift",
     *                              "nullable"=true
     *                          }
     *                    }
     *               }
     *         }
     *     }
     * )
     * @Assert\Type("array")
     * @Assert\Valid()
     */
    public ?array $pictures = [];


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Studio-Video",
     *              "type" = "object",
     *              "properties" = {
     *                  "url" = {
     *                      "type" = "string",
     *                      "example"="https://www.youtube.com/xyiasoiid/",
     *                      "description" = "Video-Url (Link)",
     *                      "nullable"=true
     *                  },
     *                  "title" = {
     *                      "type" = "string",
     *                      "example" = "Unser tolles Studio video",
     *                      "description" = "Video title",
     *                      "nullable"=true
     *                  },
     *                  "type" = {
     *                      "type" = "string",
     *                      "example" = "youtube",
     *                      "description" = "Video type",
     *                      "enum"={"youtube","vimeo","asset"}
     *                  }
     *              },
     *              "additionalProperties"=false
     *         }
     *     }
     * )
     * @var array<string,string>|null
     */
    public ?array $video = null;

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
     *              "example"="Register at reception",
     *              "maxLength"=10000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="10000")
     * @Assert\Type("string")
     */
    public ?string $checkinInformation = null;

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
    public int $categoryPrimary = 0;

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
    public array $categories;

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
     *              "description"="Feiertage o.ä.",
     *              "example"="Jeden zweiten Sonntag geschlossen, Heiligabend geöffnet",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $holidays = null;

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
