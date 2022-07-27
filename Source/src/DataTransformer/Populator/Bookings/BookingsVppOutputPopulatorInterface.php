<?php


namespace App\DataTransformer\Populator\Bookings;


use App\Dto\VPP\Events\BookingDto;
use Pimcore\Model\DataObject\CourseUser;

interface BookingsVppOutputPopulatorInterface
{
    public function populate(CourseUser $source, BookingDto $target): BookingDto;
}
