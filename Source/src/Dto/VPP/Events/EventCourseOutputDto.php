<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class EventCourseOutputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Termin ID",
     *              "example"="556641",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public ?int $eventId = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Course name",
     *              "example"="Ein neuer Kurs",
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
     *              "description"="Streaming providerr",
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
     *              "description"="Stream access link",
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
     *              "description"="Password",
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
     *              "description"="Additional informations",
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
     *              "description"="Event Date (Y-m-d)",
     *              "example"="2021-12-24",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Date
     */
    public ?string $date = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Time (H-i)",
     *              "example"="17:00",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Regex(
     *     pattern="/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/",
     *     match=true,
     * )
     */
    public ?string $time = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Timezone for the given dates/times",
     *              "example"="Europe/Berlin",
     *              "maxLength"=30,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Timezone
     */
    public string $timeZone = "Europe/Berlin";


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Duration (Minutes)",
     *              "example"="90",
     *              "maxLength"=3,
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Length(max="3")
     * @Assert\Type("integer")
     */
    public ?int $duration = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Capacity",
     *              "example"="120",
     *              "maxLength"=5,
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Length(max="5")
     * @Assert\Type("integer")
     */
    public ?int $capacity = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Bookings",
     *              "example"="110",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $bookings;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Cancelled",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $cancelled = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Published",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $published = null;

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
}
