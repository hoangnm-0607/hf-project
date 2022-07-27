<?php


namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
class DatedCoursesDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Timestamp of last request",
     *              "example"="1234567894",
     *              "maxLength"=30,
     *              "type"="integer"
     *         }
     *     },
     * )
     * @Assert\Length(max="30")
     * @Assert\Type("integer")
     */
    public int $lastUpdateTimestamp;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("array")
     *
     * @var CoursesDto[]
     */
    public array $data = [];


}
