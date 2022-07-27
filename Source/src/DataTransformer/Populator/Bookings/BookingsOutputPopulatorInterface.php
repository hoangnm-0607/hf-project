<?php


namespace App\DataTransformer\Populator\Bookings;


use App\Dto\BookingOutputDto;
use App\Entity\Booking;

interface BookingsOutputPopulatorInterface
{
    public function populate(Booking $source,  BookingOutputDto $target): BookingOutputDto;
}
