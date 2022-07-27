<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class EventCancelInputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Canceles the event with all related bookings",
     *              "example"="true",
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Type("boolean")
     */
    public bool $cancel = true;
}
