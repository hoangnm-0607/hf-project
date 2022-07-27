<?php

namespace App\Controller;

use App\Entity\PartnerProfile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/system/lamda")
 * */
class LamdaController extends AbstractController
{
    /**
     * @Route ("/validate_partner_id", methods={"GET"}, name="system_validate_partner_id")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function validatePartnerIdAction(Request $request): JsonResponse
    {
        $casPartnerId = $request->get('partner_id');
        $startCode = $request->get('start_code');

        if (!$casPartnerId || !$startCode || !is_numeric($casPartnerId) ) {
            return new JsonResponse(['error' => 'Missing parameter partner_id or start_code'], Response::HTTP_BAD_REQUEST);
        }

        foreach (PartnerProfile::getByPartnerID($casPartnerId) as $partner) {
            if ($partner->getStartCode() == $startCode) {
                return new JsonResponse([
                    'publicId' => $partner->getCASPublicID(),
                    'name' => $partner->getName(),
                ]);
            }
        }

        return new JsonResponse(['error' => 'Unknown partner_id/start_code combination'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Route ("/partner_name/{publicId}", methods={"GET"}, name="system_partner_name", requirements={"publicId"="\w+"})
     *
     * @param string $publicId
     * @return JsonResponse
     */
    public function partnerNameAction(string $publicId): JsonResponse
    {

        foreach (PartnerProfile::getByCASPublicID($publicId) as $partner) {
            if ($partner) {
                return new JsonResponse([
                    'name' => $partner->getName(),
                ]);
            }
        }

        return new JsonResponse(['error' => 'Unknown publicId'], Response::HTTP_NOT_FOUND);
    }
}
