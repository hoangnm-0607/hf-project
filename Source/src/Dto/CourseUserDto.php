<?php


namespace App\Dto;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class CourseUserDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Vorname",
     *              "example"="Max",
     *              "maxLength"=40,
     *              "type"="string",
     *              "nullable"=false
     *         }
     *     }
     * )
     * @Assert\Length(max="40")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @var string|null
     */
    public ?string $firstName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Nachname",
     *              "example"="Muster",
     *              "maxLength"=40,
     *              "type"="string",
     *              "nullable"=false
     *         }
     *     }
     * )
     * @Assert\Length(max="40")
     * @Assert\Type("string")
     * @Assert\NotNull
     * @var string|null
     */
    public ?string $lastName;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="User ID",
     *              "example"="6ca13d52ca70c883e0f0bb101e42e8624de51db2",
     *              "maxLength"=40,
     *              "type"="string",
     *              "nullable"=false
     *         }
     *     }
     * )
     * @Assert\Length(max="40")
     * @Assert\Type("string")
     * @Assert\NotNull
     */
    public string $userId;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Arbeitgeber",
     *              "example"="Hansefit",
     *              "maxLength"=40,
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Length(max="40")
     * @Assert\Type("string")
     * @var string|null
     */
    public ?string $companyName;

}
