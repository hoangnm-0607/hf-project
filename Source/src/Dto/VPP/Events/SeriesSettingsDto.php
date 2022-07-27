<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class SeriesSettingsDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="End Date (YYYY-MM-DD)",
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
     *          "openapi_context"={
     *              "description" = "Series weekdays as number starting with monday=1",
     *              "example" = {1,5},
     *              "type"="array",
     *              "items"= {"type"="integer"}
     *         }
     *     }
     * )
     *
     * @Assert\Type("array")
     *
     * @var int[]|null
     */
    public ?array $weekdays = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Series interval in weeks 0-3",
     *              "example"="0",
     *              "type"="integer",
     *              "enum"={0,1,2,3}
     *         }
     *     },
     * )
     * @Assert\Type("integer")
     */
    public int $repetitions = 0;
}
