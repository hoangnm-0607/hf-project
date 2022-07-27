<?php

namespace Tests\Controller;

use App\Controller\AdminGeoDataController;
use App\Service\GeoCodeService;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\PartnerProfile;
use Symfony\Component\HttpFoundation\Request;

class AdminGeoDataControllerTest extends TestCase
{
    private Request|MockObject $requestMock;
    private AdminGeoDataController $adminGeoDataController;

    protected function setUp(): void
    {
        $this->requestMock = $this->createMock(Request::class);
        $geoCodeService = $this->createMock(GeoCodeService::class);
        $this->adminGeoDataController = new AdminGeoDataController($geoCodeService);
    }

    /**
     * @throws Exception
     */
    public function testRecalculateGeoDataAction_ReturnsEmpty()
    {
        $profile = $this->createMock(PartnerProfile::class);
        $this->requestMock->method('get')->willReturn($profile->getId());

        $output = $this->adminGeoDataController->recalculateGeoDataAction($this->requestMock);

        self::assertEquals([], json_decode($output->getContent(), true));
    }
}
