<?php


namespace App\Controller;


use App\Entity\Company;
use App\Message\InviteCuAdmin;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompanyAdministrationController
{

    /**
     * @Route("/company-invite-user/", methods={"POST"}, name="plugin_company_invite_user")
     * @throws Exception
     */
    public function inviteUserAction(MessageBusInterface $bus, Request $request): JsonResponse
    {
        $companyId = $request->get('company_id');
        $company = Company::getById($companyId);

        $message = new InviteCuAdmin();
        $message->setCompanyId($company->getId());
        $message->setCompanyName($company->getName());
        $message->setFirstname($request->get('firstname'));
        $message->setLastname($request->get('lastname'));
        $message->setEmail(strtolower($request->get('email')));
        $message->setPosition($request->get('position'));


        $bus->dispatch($message);

        return new JsonResponse(["success" => true], Response::HTTP_OK);
    }


}
