<?php

namespace App\Controller;

use App\Service\GeoCodeService;
use Exception;
use Pimcore\Model\DataObject\Data\GeoCoordinates;
use Pimcore\Model\DataObject\PartnerProfile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminGeoDataController
{

    private GeoCodeService $geoCodeService;

    public function __construct(GeoCodeService $geoCodeService)
    {
        $this->geoCodeService = $geoCodeService;
    }

    /**
     * @Route("/admin/recalculate-geodata/")
     * @throws Exception
     */
    public function recalculateGeoDataAction(Request $request): JsonResponse
    {
        $result = [];
        if (($objectId = $request->get('objectId')) && $partnerProfile = PartnerProfile::getById($objectId)) {

            $address = $this->geoCodeService->buildAddressQuery($partnerProfile);

            if ($coordinates = $this->geoCodeService->geoCodeAddress($address)) {
                $partnerProfile->setGeoData(new GeoCoordinates($coordinates['latitude'], $coordinates['longitude']));
            }

            if ($partnerProfile->save()) {
                $result = ['success' => true];
            }

        }

        return new JsonResponse($result);

    }

}
