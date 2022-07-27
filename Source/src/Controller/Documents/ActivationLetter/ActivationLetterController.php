<?php

namespace App\Controller\Documents\ActivationLetter;

use App\Entity\EndUser;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActivationLetterController extends FrontendController
{
    public function activationletterAction(Request $request): Response
    {
        /** @var EndUser|null $endUser */
        $endUser = $request->attributes->get('end-user');

        $writer = new PngWriter();

        $qrCode = QrCode::create(sprintf('https://app.hansefit.de/activate?code=%s', $endUser?->getActivationKey()))
            ->setEncoding(new Encoding('UTF-8'))
        ;

        $qrCodeResult = $writer->write($qrCode);

        return $this->render('activationLetter/layout.html.twig', [
            'company_name' => $endUser?->getCompany()?->getName() ?? 'example_company_name',
            'activation_key' => $endUser?->getActivationKey() ?? 'activation_key',
            'first_name' => $endUser?->getFirstname() ?? 'First_name',
            'last_name' => $endUser?->getLastname() ?? 'Last_name',
            'qr_code' => $qrCodeResult,
        ]);
    }
}
