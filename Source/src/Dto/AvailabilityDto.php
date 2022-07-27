<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class AvailabilityDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Termin ID",
     *              "example"="556641",
     *              "maxLength"=10,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("string")
     */
    public string $eventId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="verfügbare Plätze",
     *              "example"="51",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $capacity;

}
