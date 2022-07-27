<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *      shortName="Bookings Input"
 * )
 */
final class BookingInputDto
{
    /**
     * @var CourseUserDto
     * @ApiProperty ()
     */
    public CourseUserDto $user;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Termin ID",
     *              "example"="556641",
     *              "maxLength"=10,
     *              "type"="string",
     *              "nullable"=false
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("string")
     * @Assert\NotNull()
     */
    public string $eventId;

}
