<?php


namespace App\Dto\VPP\Shared;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class PaginationDto
{
    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Totalamount of events",
     *              "example"="180",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $totalCount;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of pages",
     *              "example"="6",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $pages;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="The current page number",
     *              "example"="2",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $currentPage;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Number of events per page",
     *              "example"="30",
     *              "maxLength"=10,
     *              "type"="integer"
     *         }
     *     }
     * )
     * @Assert\Length(max="10")
     * @Assert\Type("integer")
     */
    public int $itemsPerPage;

}
