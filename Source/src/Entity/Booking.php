<?php


namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\BookingsCancelController;
use App\Controller\BookingsCheckInController;
use App\Dto\BookingInputDto;
use App\Dto\BookingOutputDto;
use Pimcore\Model\DataObject\Booking as DataObjectBooking;

/**
 * @ApiResource (
 *  attributes={},
 *  shortName="Booking",
 *  normalizationContext={
 *      "allow_extra_attributes"=false,
 *  },
 *  input=BookingInputDto::class,
 *  output=BookingOutputDto::class,
 *  formats={"json"},
 *  collectionOperations={
 *      "post" = {
 *          "input"=BookingInputDto::class,
 *          "output"=BookingOutputDto::class,
 *     }
 *  },
 *  itemOperations={
 *      "checkIn" = {
 *          "method"="PUT",
 *          "path"="/bookings/checkin/{bookingId}",
 *          "controller"=BookingsCheckInController::class,
 *          "openapi_context"={
 *              "summary"="Sets check-in datetime of a Booking resource",
 *              "description"="Sets check-in datetime of a Booking resource",
 *          },
 *          "read"=false,
 *          "input"=BookingOutputDto::class,
 *          "output"=BookingOutputDto::class
 *     },
 *     "cancel" = {
 *          "method"="PUT",
 *          "path"="/bookings/cancel/{bookingId}",
 *          "controller"=BookingsCancelController::class,
 *          "openapi_context"={
 *              "summary"="Sets canceled flag of a Booking resource",
 *              "description"="Sets canceled flag of a Booking resource",
 *          },
 *          "read"=false,
 *          "input"=BookingOutputDto::class,
 *          "output"=BookingOutputDto::class
 *     },

 *   }
 * )
 */
final class Booking extends DataObjectBooking
{

    /**
     * @ApiProperty(identifier=true)
     */
    public ?string $bookingId = null;

}
