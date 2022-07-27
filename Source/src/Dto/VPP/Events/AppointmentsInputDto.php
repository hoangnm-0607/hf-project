<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use App\Dto\VPP\Assets\TitleDto;
use Symfony\Component\Validator\Constraints as Assert;

final class AppointmentsInputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Start Date (Y-m-d)",
     *              "example"="2021-12-24",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Date
     */
    public string $startDate;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Time (H:i)",
     *              "example"="17:00",
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Time
     */
    public string $time;

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
     * @Assert\Length(max="30")
     * @Assert\Type("string")
     */
    public string $timeZone;

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
    public ?int $duration;

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
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Events\SeriesSettingsDto")
     *
     * @var ?SeriesSettingsDto
     */
    public ?SeriesSettingsDto $seriesSettings = null;
}
