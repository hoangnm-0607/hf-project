<?php

namespace Tests\Controller;

use App\Controller\AdminController;
use App\Entity\Booking;
use App\Repository\SingleEventRepository;
use Carbon\Carbon;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Data\ObjectMetadata;
use Pimcore\Model\DataObject\SingleEvent;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminControllerTest extends TestCase
{
    private Request|MockObject $requestMock;
    private SingleEventRepository $singleEventRepository;
    private AdminController $adminController;

    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(Request::class);
        $this->singleEventRepository = $this->createMock(SingleEventRepository::class);
        $this->adminController = new AdminController($this->singleEventRepository);
    }

    public function testCheckIfCourseEventIsModifiable_ReturnsPastDate()
    {
        $event = $this->createMock(SingleEvent::class);
        $event->method('getCourseDate')->willReturn(new Carbon('yesterday'));

        $this->singleEventRepository->method('getOneSingleEventById')->willReturn($event);
        $this->requestMock->query = new ParameterBag([
            'id' => $event->getId()
        ]);
        $output = $this->adminController->checkIfCourseEventIsModifiable($this->requestMock);

        self::assertEquals(Response::HTTP_OK, $output->getStatusCode());
        self::assertEquals([
            'message' => 'pastDate'
        ], json_decode($output->getContent(), true));
    }

    public function testCheckIfCourseEventIsModifiable_ReturnsBookings()
    {
        $event = $this->createMock(SingleEvent::class);
        $event->method('getCourseDate')->willReturn(new Carbon('+2 days'));
        $event->method('getBookings')->willReturn(true);

        $this->singleEventRepository->method('getOneSingleEventById')->willReturn($event);
        $this->requestMock->query = new ParameterBag([
            'id' => $event->getId()
        ]);
        $output = $this->adminController->checkIfCourseEventIsModifiable($this->requestMock);

        self::assertEquals(Response::HTTP_OK, $output->getStatusCode());
        self::assertEquals([
            'message' => 'bookings'
        ], json_decode($output->getContent(), true));
    }
}
