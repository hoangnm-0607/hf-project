<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use App\Dto\VPP\Shared\PaginationDto;
use Symfony\Component\Validator\Constraints as Assert;

class EventListOutputDto
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
     * @Assert\Type("App\Dto\VPP\Events\EventOutputDto")
     *
     * @var EventOutputDto[]
     */
    public array $result = [];

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Events\AvailableFiltersDto")
     *
     * @var AvailableFiltersDto
     */
    public AvailableFiltersDto $availableFilters;
}
