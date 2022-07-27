<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class AvailableFiltersDto
{

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Filterable course names",
     *              "example"={"Kurs1", "Kurs2"},
     *              "type"="string"
     *         }
     *     }
     * )
     * @Assert\Type("array")
     *
     * @var string[]
     */
    public array $courseName;

}
