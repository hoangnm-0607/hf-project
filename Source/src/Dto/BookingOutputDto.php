<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class BookingOutputDto
{
    /**
     * @ApiProperty(
     *     identifier=true,
     *     attributes={
     *         "openapi_context"={
     *              "description"="Buchungs ID",
     *              "example"="Pumping_Iron_2444_21-06-30-00-387-366",
     *              "maxLength"=80,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="80")
     * @Assert\Type("string")
     */
    public string $bookingId;

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

}
