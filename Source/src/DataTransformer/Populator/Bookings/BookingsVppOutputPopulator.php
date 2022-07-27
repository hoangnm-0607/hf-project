<?php


namespace App\DataTransformer\Populator\Bookings;


use App\Dto\VPP\Events\BookingDto;
use Pimcore\Model\DataObject\CourseUser;

class BookingsVppOutputPopulator implements BookingsVppOutputPopulatorInterface
{

    public function populate(CourseUser $source, BookingDto $target): BookingDto
    {
        $target->firstname = $source->getFirstname();
        $target->lastname = $source->getLastname();
        $target->company = $source->getCompany();

        return $target;
    }
}
