<?php


namespace App\Dto\VPP\Dashboard;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class DashboardStatsDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Totalamount of performed events",
     *              "example"="180",
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Type("integer")
     */
    public int $performedEvents;


    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of events for the last 3 caleder weeks",
     *              "example"={
     *                  {"week"=51, "amount"=6},
     *                  {"week"=52, "amount"=8},
     *                  {"week"=1, "amount"=4},
     *              },
     *              "type"="array",
     *         }
     *     },
     * )
     * @Assert\Type("array")
     *
     */
    public array $eventsPerWeek;


    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Dashboard\DashboardEventDto")
     *
     * @var DashboardEventDto[]
     */
    public array $top3Events = [];

}
