<?php


namespace App\Dto\VPP\Dashboard;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class DashboardEventDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Course name",
     *              "example"="Ein toller Kurs",
     *              "maxLength"=500,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="500")
     * @Assert\Type("string")
     */
    public string $courseName;


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
    public string $eventDate;

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
    public string $eventTime;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of bookings",
     *              "example"="45",
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
     *              "description"="Number of checkins",
     *              "example"="45",
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $checkins;
}
