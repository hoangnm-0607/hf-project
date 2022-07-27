<?php

namespace App\Controller;

use App\Entity\PartnerProfile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/api/partners")
 * */
class PartnerValidationController extends AbstractController
{
    /**
     * @Route ("/validate_partner_id", methods={"GET"}, name="validate_partner_id")
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
                return new JsonResponse(['success' => true, 'name' => $partner->getName()]);
            }
        }

        return new JsonResponse(['success' => false]);
    }
}
