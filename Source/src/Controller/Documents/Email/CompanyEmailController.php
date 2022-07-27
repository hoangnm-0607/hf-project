<?php

namespace App\Controller\Documents\Email;

use Pimcore\Controller\FrontendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyEmailController extends FrontendController
{
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
}
