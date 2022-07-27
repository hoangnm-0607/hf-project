<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class BookingDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Firstname",
     *              "example"="Karl",
     *              "maxLength"=50,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="50")
     * @Assert\Type("string")
     */
    public ?string $firstname = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Firstname",
     *              "example"="Meier",
     *              "maxLength"=50,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="50")
     * @Assert\Type("string")
     */
    public ?string $lastname = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Firstname",
     *              "example"="Hansefit GmbH",
     *              "maxLength"=50,
     *              "type"="string"
     *         }
     *     },
     * )
     * @Assert\Length(max="50")
     * @Assert\Type("string")
     */
    public ?string $company = null;
}
