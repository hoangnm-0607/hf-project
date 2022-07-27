<?php


namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class DatedPartnerProfileDto
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
     * @var PartnerProfileDto[]
     */
    public array $data = [];


}
