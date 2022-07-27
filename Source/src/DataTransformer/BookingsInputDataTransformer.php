<?php


namespace App\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\BookingInputDto;
use App\Entity\Booking;

class BookingsInputDataTransformer implements DataTransformerInterface
{
    private iterable $populators;

    public function __construct(iterable $populators)
    {
        $this->populators = $populators;
    }

    /**
     * @param BookingInputDto $object
     */
    public function transform($object, string $to, array $context = []): Booking
    {
        $target = new Booking();

        foreach ($this->populators as $populator) {
            $target = $populator->populate($object, $target);
        }

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Booking) {
            return false;
        }

        return $to === Booking::class && (
            ($data instanceof BookingInputDto) ||
            ($context['input']['class'] ?? null) === BookingInputDto::class
        );
    }
}
