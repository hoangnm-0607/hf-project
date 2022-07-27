<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class EventDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Termin ID",
     *              "example"="112250",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $eventId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Streaming-Anbieter",
     *              "example"="Zoom",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $streamingHost = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Zugangslink",
     *              "example"="https://zoom/url/zum/kurs",
     *              "maxLength"=190,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="190")
     * @Assert\Type("string")
     */
    public ?string $streamLink = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Passwort",
     *              "example"="abc123",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public ?string $streamPassword = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Zusatzinfos",
     *              "example"="Heute abweichende Kursleitung!",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $additionalInformation = null;


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Meeting-ID",
     *              "example"="123456",
     *              "maxLength"=1000,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="1000")
     * @Assert\Type("string")
     */
    public ?string $meetingId = null;


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Datum (Timestamp)",
     *              "example"="1623309118",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $courseDate;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Dauer (Minuten)",
     *              "example"="90",
     *              "maxLength"=3,
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Length(max="3")
     * @Assert\Type("integer")
     */
    public?int $duration;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="abgesagt",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public bool $cancelled = false;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="ausgebucht",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public bool $fullybooked = false;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Buchungs ID",
     *              "example"="566654",
     *              "maxLength"=10,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("string")
     */
    public ?string $bookingId = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="storniert",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $userCancelled = false;
}
