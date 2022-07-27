<?php


namespace App\DataTransformer\Populator\Bookings;


use App\Dto\BookingOutputDto;
use App\Entity\Booking;
use Exception;

class BookingsOutputPopulator implements BookingsOutputPopulatorInterface
{

    /**
     * @throws Exception
     */
    public function populate( Booking $source, BookingOutputDto $target): BookingOutputDto
    {
        if($event     = $source->getEvent()) {
            $target->eventId = $event->getId();
            $target->bookingId = $source->getBookingId() ?? '';
        }

        return $target;
    }
}
