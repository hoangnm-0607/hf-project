<?php


namespace App\EventListener;


use App\Repository\SingleEventRepository;
use Exception;
use Pimcore\Event\Model\DataObjectDeleteInfoEvent;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\SingleEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class SingleEventDeletionListener
{
    private TranslatorInterface $translator;
    private SingleEventRepository $singleEventRepository;

    public function __construct(TranslatorInterface $translator, SingleEventRepository $singleEventRepository)
    {
        $this->translator = $translator;
        $this->singleEventRepository = $singleEventRepository;
    }

    public function checkDeletion(DataObjectDeleteInfoEvent $event)
    {
        if ( ($singleEvent = $event->getElement())
             && $singleEvent instanceof SingleEvent
             && ($bookings = $singleEvent->getBookings())
             && ! empty($bookings)) {
            $deletable = false;
            $reason    = $this->translator->trans('admin.object.message.deleteFailed.bookedEvents', [], 'admin');

            $event->setDeletionAllowed($deletable);

            if ($reason) {
                $event->setReason($reason);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function updateModificationDateOfParentCourse(DataObjectEvent $event): void
    {
        if ( ($singleEvent = $event->getElement())
            && $singleEvent instanceof SingleEvent
            && $course = $singleEvent->getParentCourse() ) {
            $singleEvents = array_filter(
                $this->singleEventRepository->getAllSingleEventsByCourse($course),
                function ($event) use ($singleEvent) {
                    return $event !== $singleEvent;
                }
            );
            if(count($singleEvents) > 0) {
                $course->setSingleEvents(
                    $singleEvents
                );
                $course->save();
            }
        }
    }
}
