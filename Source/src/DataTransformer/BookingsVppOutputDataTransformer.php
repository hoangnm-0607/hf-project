<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\VPP\Events\BookingDto;
use App\Entity\SingleEvent;
use App\Repository\BookingRepository;
use App\Service\SingleEventService;
use ArrayIterator;
use Exception;
use Pimcore\Model\DataObject\Booking;

class BookingsVppOutputDataTransformer implements DataTransformerInterface
{

    private iterable $populators;
    private BookingRepository $bookingRepository;
    private SingleEventService $singleEventService;

    public function __construct(iterable $populators, BookingRepository $bookingRepository, SingleEventService $singleEventService)
    {
        $this->populators = $populators;
        $this->bookingRepository = $bookingRepository;
        $this->singleEventService = $singleEventService;
    }

    /**
     * @param SingleEvent $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : iterable
    {

        $eventList = $this->bookingRepository->getBookingsForEvent($object, $this->singleEventService->checkIfThisIsAnArchivedEvent($object));

        $bookings = [];
        foreach ($eventList as $booking) {
            foreach ($this->populators as $populator) {
                $bookingDto = new BookingDto();
                $bookingDto = $populator->populate($booking->getUser(), $bookingDto);
                $bookings[] = $bookingDto;
            }
        }

        return new ArrayIterator($bookings);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return BookingDto::class === $to && $data instanceof SingleEvent;
    }
}
