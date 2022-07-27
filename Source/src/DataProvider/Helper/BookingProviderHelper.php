<?php


namespace App\DataProvider\Helper;



use App\Dto\BookingOutputDto;
use Exception;
use Pimcore\Model\DataObject\Booking;

class BookingProviderHelper
{

    public function setBookingDto(?Booking $booking): BookingOutputDto
    {
        $bookingDto            = new BookingOutputDto();
        $bookingDto->bookingId = $booking->getBookingId() ?? '';

        $event                = $booking->getEvent();
        $bookingDto->eventId = $event->getId();

        return $bookingDto;
    }

    /**
     * @throws Exception
     */
    public function save(?Booking $booking): ?Booking
    {
        return $booking->save();
    }

}
