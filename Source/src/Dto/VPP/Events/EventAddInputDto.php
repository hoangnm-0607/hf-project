<?php


namespace App\Dto\VPP\Events;


use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

final class EventAddInputDto
{
    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Events\AppointmentsInputDto")
     *
     * @var ?AppointmentsInputDto
     */
    public ?AppointmentsInputDto $appointments = null;

    /**
     * @ApiProperty(readableLink=true)
     * @Assert\Type("App\Dto\VPP\Events\StreamSettingsDto")
     *
     * @var ?StreamSettingsDto
     */
    public ?StreamSettingsDto $streamSettings = null;

    /**
     * @ApiProperty(
     *     attributes={
     *         "openapi_context"={
     *              "description"="Published",
     *              "example"="false",
     *              "maxLength"=1,
     *              "type"="boolean"
     *         }
     *     },
     * )
     * @Assert\Length(max="1")
     * @Assert\Type("boolean")
     */
    public ?bool $published = null;
}
