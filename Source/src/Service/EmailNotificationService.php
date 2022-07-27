<?php

namespace App\Service;

use App\Entity\PartnerProfile;
use Pimcore\Mail;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\SingleEvent;
use Pimcore\Model\User\Listing;

class EmailNotificationService
{
    private DataObjectService $dataObjectService;
    private CognitoDynamoDbManagementService $cognitoDynamoDbManagementService;

    public function __construct(DataObjectService $dataObjectService, CognitoDynamoDbManagementService $cognitoDynamoDbManagementService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->cognitoDynamoDbManagementService = $cognitoDynamoDbManagementService;
    }

    public function sendCourseLinkNotification(SingleEvent $singleEvent, $notificationCounter): bool
    {
        /** @var Course $course */
        /** @var PartnerProfile $partner */
        if (($course = $singleEvent->getParentCourse())
            && ($partner = $this->dataObjectService->getRecursiveParent($course, PartnerProfile::class ))
            && ($publicId = $partner->getCASPublicID())
            && $cognitoUsers = $this->cognitoDynamoDbManagementService->getActiveCognitoUsersByPublicId($publicId)) {

                foreach ($cognitoUsers as $cognitoUser) {
                    if ($email = $cognitoUser['email']) {
                        $mail = new Mail();
                        $mail->to($email);
                        $mail->subject(sprintf('Erinnerung %d von 2: Kurstermin "%s" startet bald, Streaming-Link fehlt noch. ', $notificationCounter, $course->getCourseName() ));
                        $mail->text(sprintf('Hinweis: für den in den nächsten 24 Stunden startenden Termin #%s des Kurses "%s" ist noch kein Streaming-Link hinterlegt!
                        Sollte bis 15 Minuten vor Kursbeginn kein Link hinterlegt sein, wird der Termin automatisch abgesagt.', $singleEvent->getId(), $course->getCourseName()));
                        $mail->send();
                    }
                }
                return true;
            }
        return false;
    }

    public function sendNoAvailableVoucherNotification(Listing $users, string $productName): void
    {
        foreach ($users as $user) {
            if ($email = $user->getEmail()) {
                $mail = new Mail();
                $mail->to($email);
                $mail->subject(sprintf('Keine Gutscheincodes für %s mehr vorhanden', $productName ));
                $mail->text(sprintf('Das Produkt %s wurde automatisch unveröffentlicht, da keine freien
                Gutscheincodes für dieses Produkt mehr vorhanden sind. Bitte lade neue Gutscheincodes hoch und
                veröffentliche das Produkt im Anschluss wieder.', $productName));
                $mail->send();
            }
        }
    }

    public function sendSoonExpiringVoucherNotification(Listing $notifiableUsers, ?string $productName)
    {
        foreach ($notifiableUsers as $user) {
            if ($email = $user->getEmail()) {
                $mail = new Mail();
                $mail->to($email);
                $mail->subject(sprintf('Bald keine gültigen Gutscheincodes für %s mehr vorhanden', $productName ));
                $mail->text(sprintf('Das Produkt %s wird bald automatisch unveröffentlicht, da  keine gültigen
                Gutscheincodes dann für das Produkt vorhanden sind. Bitte lade neue Gutscheincodes hoch und
                veröffentliche das Produkt im Anschluss wieder.', $productName));
                $mail->send();
            }
        }
    }
}
