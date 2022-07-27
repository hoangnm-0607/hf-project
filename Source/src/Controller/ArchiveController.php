<?php


namespace App\Controller;


use App\Service\ArchiveService;
use Carbon\Carbon;
use Exception;
use Pimcore\Bundle\AdminBundle\HttpFoundation\JsonResponse;
use Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArchiveController
{
    private ArchiveService $archiveService;
    private TranslatorInterface $translator;
    private bool $canArchive = true;
    private string $message = '';

    public function __construct(TranslatorInterface $translator, ArchiveService $archiveService)
    {
        $this->translator = $translator;
        $this->archiveService = $archiveService;
    }

    /**
     * @Route ("/object-show-archive-button/", methods="GET")
     */
    public function checkShowArchiveButtonAction(Request $request): JsonResponse
    {
        $show = true;
        if(($id = $request->query->getInt('id'))
           && ($object = DataObject::getById($id))
           && stripos($object->getFullPath(), 'Archive')) {
                $show = false;
            }
        return new JsonResponse($show, Response::HTTP_OK);
    }

    /**
     * @Route ("/object-do-archive/", methods="GET")
     *
     * @throws Exception
     */
    public function archiveAction(Request $request): JsonResponse
    {
        $id = $request->query->getInt('id');
        $object = DataObject::getById($id);

        if($object instanceof DataObject\Course) {
            $this->checkCourseForFutureEvents($object);

        } else if ($object instanceof DataObject\SingleEvent) {
            $this->checkEventForBookings($object);

        } else {
            $this->canArchive = false;
            $this->message    = 'admin.object.message.archiveFailed.unknownObjectType';
        }

        if($this->canArchive) {
            $this->message = $this->archiveService->archiveCourseOrEvent($object) ?
                'admin.object.message.archiveSuccess' :
                'admin.object.message.archiveFailed.serviceFailed';
        }

        $response = [
            'canArchive' => $this->canArchive,
            'message' => $this->translator->trans($this->message, [], 'admin')
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * Checks if there are already events for this course in the future
     */
    private function checkCourseForFutureEvents(DataObject\Course $object): void
    {
        if ($events = $object->getSingleEvents()) {
            foreach ($events as $event) {
                if ($event->getCourseDate() > new Carbon()) {
                    $this->canArchive = false;
                    $this->message    = 'admin.object.message.archiveFailed.futureEventDates';
                }
            }
        }
    }

    /**
     * Checks if there are future events that have already been booked
     */
    private function checkEventForBookings(DataObject\SingleEvent $object): void
    {
        if ($object->getCourseDate() >= new Carbon() && ($bookings = $object->getBookings()) && ! empty($bookings)) {
            $this->canArchive = false;
            $this->message    = 'admin.object.message.archiveFailed.bookingsForEvent';
        }
    }

}
