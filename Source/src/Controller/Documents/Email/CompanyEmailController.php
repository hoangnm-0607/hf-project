<?php

namespace App\Controller\Documents\Email;

use App\Service\FolderService;
use App\Traits\I18NServiceTrait;
use Pimcore\Controller\FrontendController;

use Pimcore\Document\Renderer\DocumentRenderer;
use Pimcore\Model\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyEmailController extends FrontendController
{
    use I18NServiceTrait;

    private FolderService $folderService;

    public function __construct(FolderService $folderService)
    {
        $this->folderService = $folderService;
    }

    /**
     * @Template
     * @param Request $request
     * @return Response
     */
    public function emailAction(Request $request): Response
    {
        return $this->render('companyEmail/defaultEmail.html.twig');
    }

    /**
     * @Template
     * @param Request $request
     * @return Response
     */
    public function emailWithLinkAction(Request $request): Response
    {
        return $this->render('companyEmail/linkEmail.html.twig', [
            'link' => 'https://www.google.de'
        ]);
    }

    /**
     * @Template
     * @param Request $request
     * @return Response
     */
    public function footerAction(Request $request): Response
    {
        return $this->render('companyEmail/snippet/footer.html.twig');
    }

    /**
     * @Template
     * @param Request $request
     * @return Response
     */
    public function headerAction(Request $request): Response
    {
        return $this->render('companyEmail/snippet/header.html.twig');
    }


    #[Route('/system/company/email', name: 'system_email', methods: ['GET'])]
    public function emailTemplate(Request $request, DocumentRenderer $documentRenderer): Response
    {
        if (!$documentKey = $request->get('document_key')) {
            return new Response(null,Response::HTTP_BAD_REQUEST);
        }

        $companyId = $request->get('company_id');
        $language = $this->i18NService->getLanguageFromRequest();

        if (null !== $companyId && $document = Document::getByPath($this->folderService->getCompanyEmailTemplatesPath($companyId) .'/'. $language . '/' . $documentKey)) {
            return new Response($documentRenderer->render($document));
        }
        elseif($document = Document::getByPath($this->folderService->getCompanyEmailTemplatesPath() . '/'. $language . '/' . $documentKey)) {
            return new Response($documentRenderer->render($document));
        }
        else {
            return new Response(null,Response::HTTP_NOT_FOUND);
        }
    }
}
