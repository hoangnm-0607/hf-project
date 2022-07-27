<?php

namespace Tests\DataTransformer\Populator\Bookings;

use App\DataTransformer\Populator\Bookings\BookingsOutputPopulator;
use App\Dto\BookingOutputDto;
use App\Entity\Booking;
use Exception;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\SingleEvent;

class BookingsOutputPopulatorTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testPopulate(): void
    {
        $populator = new BookingsOutputPopulator();
        $target    = new BookingOutputDto();

        $output = $populator->populate($this->createInput(), $target);

        self::assertEquals($this->createExpectedOutput(), $output);
    }

    private function createInput(): Booking
    {
        $event = new SingleEvent();
        $event->setId(53);

        $input = new Booking();
        $input->setEvent($event);
        $input->setBookingId(896321);

        return $input;
    }

    #[Pure] private function createExpectedOutput(): BookingOutputDto
    {
        $output = new BookingOutputDto();
        $output->eventId = 53;
        $output->bookingId = 896321;

        return $output;
    }
}
