<?php


namespace App\Dto\VPP\Courses;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class CoursesVppArchiveInputDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Archive the course",
     *              "example"="true",
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Type("boolean")
     */
    public bool $archive = true;
}
