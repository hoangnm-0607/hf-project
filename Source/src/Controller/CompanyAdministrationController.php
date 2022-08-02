<?php


namespace App\Controller;


use App\Entity\Company;
use App\Message\InviteCuAdmin;
use App\Service\FolderService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompanyAdministrationController
{

    private FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

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

    /**
     * @Route("/admin/company/create_document_folders", methods={"POST"}, name="plugin_company_create_document_folders")
     * @throws Exception
     */
    public function createDocumentFoldersAction(Request $request): JsonResponse
    {
        $companyId = $request->get('companyId');
        $templateType = $request->get('templateType');
        $company = Company::getById($companyId);

        switch ($templateType) {
            case FolderService::TEMPLATE_TYPE_EMAIL:
                $companyFolder = $this->folderService->createDocumentFolderForCompany($company, $templateType);
                if ($company->getEmailTemplates() == null) {
                    $company->setEmailTemplates($companyFolder);
                    $company->save();
                }
                break;
            case FolderService::TEMPLATE_TYPE_FAQ:
                $companyFolder = $this->folderService->createDocumentFolderForCompany($company, $templateType);
                if ($company->getFaqTemplates() == null) {
                    $company->setFaqTemplates($companyFolder);
                    $company->save();
                }
                break;
            case FolderService::TEMPLATE_TYPE_MARKETING_MATERIAL:
                $companyFolder = $this->folderService->createDocumentFolderForCompany($company, $templateType);
                if ($company->getMarketingMaterialTemplates() == null) {
                    $company->setMarketingMaterialTemplates($companyFolder);
                    $company->save();
                }
                break;
            case FolderService::TEMPLATE_TYPE_ACTIVATION_LETTER:
                $companyFolder = $this->folderService->createDocumentFolderForCompany($company, $templateType);
                if ($company->getActivationLetterTemplates() == null) {
                    $company->setActivationLetterTemplates($companyFolder);
                    $company->save();
                }
                break;
            default:
                return new JsonResponse(["success" => false, "error" => 'Unknown templateType'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(["success" => true, "folderId" => $companyFolder->getId()], Response::HTTP_OK);
    }


}
