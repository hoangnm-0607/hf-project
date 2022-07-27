<?php


namespace App\DataPersister\Helper;


use App\Entity\Booking;
use Exception;

class BookingPersisterHelper
{
    /**
     * @throws Exception
     */
    public function savePreparedBooking(Booking $booking): ?Booking
    {
        return $booking->save();
    }

}
