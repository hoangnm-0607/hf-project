<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\AvailabilityDto;
use Pimcore\Model\DataObject\SingleEvent;

/**
 * @ApiResource (
 *  attributes={},
 *  shortName="Availability",
 *  normalizationContext={
 *      "allow_extra_attributes"=false
 *  },
 *  output=AvailabilityDto::class,
 *  formats={"json"},
 *  collectionOperations={"get"},
 *  itemOperations={"get"}
 * )
 */
final class Availability extends SingleEvent
{
    /**
     * @ApiProperty(identifier=true)
     */
    protected ?string $eventId;

    public function getEventId(): ?string
    {
        return $this->eventId;
    }

}
