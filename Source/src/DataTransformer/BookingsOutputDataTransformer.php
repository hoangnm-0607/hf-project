<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\BookingOutputDto;
use App\Entity\Booking;

class BookingsOutputDataTransformer implements DataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    /**
     * @param Booking $object
     */
    public function transform($object, string $to, array $context = []): BookingOutputDto
    {
        $target = new BookingOutputDto();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === BookingOutputDto::class && $data instanceof Booking;
    }
}
