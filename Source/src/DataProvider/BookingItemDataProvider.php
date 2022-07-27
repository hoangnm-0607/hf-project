<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Booking;
use App\Repository\BookingRepository;

class BookingItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository )
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []):?\Pimcore\Model\DataObject\Booking
    {

        return $this->bookingRepository->getOneBookingByBookingId($id);

    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Booking::class === $resourceClass;
    }
}
