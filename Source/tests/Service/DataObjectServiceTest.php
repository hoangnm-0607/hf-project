<?php

namespace Tests\Service;

use App\Service\DataObjectService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\DataObject\SingleEvent;

class DataObjectServiceTest extends TestCase
{

    protected MockObject|Course $courseMock;
    protected Folder|MockObject $folderMock;
    protected MockObject|SingleEvent $eventMock;

    protected function setUp(): void
    {
        $this->courseMock = $this->createMock(Course::class);
        $this->folderMock = $this->createMock(Folder::class);
        $this->eventMock = $this->createMock(SingleEvent::class);

        $this->eventMock->method('getParent')->willReturn($this->folderMock);
        $this->folderMock->method('getParent')->willReturn($this->courseMock);
    }

    /**
     * @test
     */
    public function GetRecursiveParent_ReturnsCourseParent():void
    {
        $dataObjectService = new DataObjectService();
        $output = $dataObjectService->getRecursiveParent($this->eventMock, Course::class);

        self::assertEquals($this->courseMock, $output);
    }
}
