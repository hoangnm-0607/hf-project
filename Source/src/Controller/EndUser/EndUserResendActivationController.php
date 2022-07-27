<?php

namespace App\Controller\EndUser;

use App\Entity\EndUser;
use App\Message\EndUserActivationMessage;
use App\Service\Company\CompanyService;
use App\Service\File\PdfFileService;
use App\Traits\I18NServiceTrait;
use App\Traits\MessageDispatcherTrait;
use Pimcore\Model\Document\Printpage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndUserResendActivationController
{
    use MessageDispatcherTrait;
    use I18NServiceTrait;

    private PdfFileService $pdfFileService;
    private CompanyService $companyService;

    public function __construct(PdfFileService $pdfFileService, CompanyService $companyService)
    {
        $this->pdfFileService = $pdfFileService;
        $this->companyService = $companyService;
    }

    public function __invoke(Request $request): JsonResponse|Response
    {
        $endUser = $request->attributes->get('data');

        if (!$endUser instanceof EndUser) {
            throw new \LogicException('There are not end-user in data attribute!');
        }

        if (!$endUser->isPermittedToActivate()) {
            throw new  BadRequestHttpException('user_can_not_be_activated');
        }

        $language = $this->i18NService->getLanguageFromRequest();
        $companyId = $endUser->getCompany()?->getId();

        $document = $this->companyService->findEndUserActivationTemplate($companyId, $language);
        if (!$document instanceof Printpage) {
            throw new NotFoundHttpException('Activation template not found!');
        }

        $pdfFile = $this->pdfFileService->createEndUserActivationPdfFile($document, $endUser);

        $email = $endUser->getPrivateEmail() ?? $endUser->getBusinessEmail();

        $contentType = $request->headers->get('content-type');

        $code = Response::HTTP_OK;

        if ('application/json' === $contentType) {
            if (is_string($email)) {
                $this->messageBus->dispatch(new EndUserActivationMessage($pdfFile, $email));

                return new JsonResponse(['sent' => true], $code);
            }

            $code = Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
        }

        if ('application/pdf' === $contentType || Response::HTTP_UNSUPPORTED_MEDIA_TYPE === $code) {
            return new Response($pdfFile, $code, ['Content-Type' => 'application/pdf']);
        }

        throw new BadRequestHttpException('wrong content type');
    }
}
