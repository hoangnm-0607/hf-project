<?php


namespace App\Dto\VPP\Courses;


use ApiPlatform\Core\Annotation\ApiProperty;
use App\Dto\VPP\Shared\PaginationDto;
use Symfony\Component\Validator\Constraints as Assert;

class CoursesListOutputDto
{
    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Shared\PaginationDto")
     *
     * @var PaginationDto
     */
    public PaginationDto $pagination;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Courses\CoursesVppOutputDto")
     *
     * @var CoursesVppOutputDto[]
     */
    public array $result = [];
}
