<?php


namespace App\DataTransformer\Populator\Bookings;


use App\Dto\BookingInputDto;
use App\Entity\Booking;

interface BookingsInputPopulatorInterface
{
    public function populate(BookingInputDto $source, Booking $target): Booking;
}
