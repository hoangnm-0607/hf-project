<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class SeriesOutputDto
{
    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Events\EventOutputDto")
     *
     * @var EventOutputDto[]
     */
    public array $events = [];

    /**
     * @ApiProperty(
     *     attributes={
     *          "openapi_context"={
     *              "description" = "Dates which could not be created",
     *              "example" = {"2021-12-01 15:00:00","2021-12-02 15:00:00"},
     *              "type"="array",
     *              "items"= {"type"="string"}
     *         }
     *     }
     * )
     *
     * @Assert\Type("array")
     *
     * @var string[]|null
     */
    public ?array $failedDates = null;

}
