<?php

namespace App\Service;

use App\Repository\SingleEventRepository;
use Exception;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\SingleEvent;

class PushNotificationService
{
    const MESSAGE_TYPE_CANCELLED = 'CANCELLED';
    const MESSAGE_TYPE_START = 'START';

    private Messaging $messaging;
    private TranslatorService $translatorService;

    private array $languages = ['de', 'en'];
    private SingleEventRepository $singleEventRepository;
    private ?string $notificationEnv;

    public function __construct(Messaging $messaging, TranslatorService $translatorService, SingleEventRepository $singleEventRepository)
    {
        $this->messaging = $messaging;
        $this->translatorService = $translatorService;
        $this->singleEventRepository = $singleEventRepository;
        $this->notificationEnv = $_ENV['FIREBASE_TOPIC_SUFFIX'];
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     * @throws Exception
     */
    public function sendMessage(int $eventId, string $type): bool
    {
        $notificationTitle = '';
        $notificationBody = '';
        $messages = [];

        if (($singleEvent = $this->singleEventRepository->getOneSingleEventById($eventId))
            && $course = $singleEvent->getParentCourse()) {

            foreach ($this->languages as $language) {

                if ($type == self::MESSAGE_TYPE_CANCELLED) {
                    $notificationTitle = $this->translatorService->getAdminTrans('notification.title.cancelled', $language);
                    $notificationBody  = $this->translatorService->getAdminTrans('notification.body.cancelled', $language);

                } elseif ($type == self::MESSAGE_TYPE_START) {
                    $notificationTitle = $this->translatorService->getAdminTrans('notification.title.starting', $language);
                    $notificationBody  = $this->translatorService->getAdminTrans('notification.body.starting', $language);
                }

                $notificationBody  = $this->replaceCoursePlaceholder($course, $singleEvent, $language, $notificationBody);

                $topic = $this->createTopic($eventId, $language);

//                  For notification testing uncomment the next three lines, run the notificate command, re-vert the lines and run the command again
//                $deviceToken = 'e941GFessg_mRFu-HYragB:APA91bEaB24RljR0oKmya_ebMG60gpEJUty_HWtZVz2rY2U8KMayyKGezYigxb8UlmxilFJUPCWqu8zF1OwblTn4hkN45dmjG281M0uFo5lIKAVYFUF5I6UysjRowr6hoxORop1k-AVK';
//                $this->messaging->subscribeToTopic($topic, $deviceToken);
//                return true;

                $messages[] = Messaging\CloudMessage::withTarget('topic', $topic)
                                         ->withNotification(Messaging\Notification::create($notificationTitle,$notificationBody))
                                         ->withDefaultSounds()
                                         ->withData(['topic' => $topic,'type' => $type]);
            }
        }


        return (bool) $this->messaging->sendAll($messages);
    }

    private function createTopic(int $eventId, mixed $language): string
    {
        $topic = $eventId . '_' . strtoupper($language);
        if ($this->notificationEnv) {
            $topic = $topic . '_' . strtoupper($this->notificationEnv);
        }

        return $topic;
    }

    private function replaceCoursePlaceholder(Course $course, SingleEvent $singleEvent, string $language, string $notificationBody): string {
        return str_replace(
            [
                '<Kursname>',
                '<Datum>',
                '<Uhrzeit>'
            ],
            [
                $course->getCourseName($language),
                $singleEvent->getCourseDate()->isoFormat('DD.MM.YY'),
                $singleEvent->getCourseStartTime()
            ],
            $notificationBody
        );
    }

}
