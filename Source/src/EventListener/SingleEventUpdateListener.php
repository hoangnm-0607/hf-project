<?php


namespace App\EventListener;


use App\Exception\NegativeCapacityException;
use App\Exception\ObjectPublishingException;
use App\Exception\ObjectSavingForbiddenException;
use App\Service\DataObjectService;
use App\Service\SingleEventService;
use Carbon\Carbon;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\SingleEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class SingleEventUpdateListener
{
    const MODIFYABLE_FIELDS = ['CourseDate', 'CourseStartTime', 'Duration', 'Cancelled', 'AdditionalInformation', 'Parent'];
    private DataObjectService $dataObjectService;
    private TranslatorInterface $translator;
    private SingleEventService $singleEventService;

    public function __construct(DataObjectService $dataObjectService, TranslatorInterface $translator, SingleEventService $singleEventService)
    {
        $this->dataObjectService = $dataObjectService;
        $this->translator = $translator;
        $this->singleEventService = $singleEventService;
    }

    /**
     * If one of the above fields is changed, set a new DataModificationDate.
     * We do this, to have a modification date field that's independent of o_modificationDate
     *
     * @param DataObjectEvent $event
     *
     * @throws Exception
     */
    public function checkIfDataModificationDateHasToBeChanged(DataObjectEvent $event): void
    {
        if( false === $this->isCallFromAutoSave($event)
            && !$event->hasArgument('isRecycleBinRestore')
            && ($object = $event->getObject())
            && $object instanceof SingleEvent
            && ($oldEvent = SingleEvent::getById($object->getId(), true))) {
                foreach (self::MODIFYABLE_FIELDS as $fieldName) {
                    $getter = 'get' . $fieldName;
                    if ($oldEvent->$getter() != $object->$getter()) {
                        $object->setDataModificationDate(new Carbon());
                        break;
                    }
                }
            }
    }

    public function setOrUpdateWeekdayAndStartTimestamp(DataObjectEvent $event): void
    {
        if(false === $this->isCallFromAutoSave($event)
           && !$event->hasArgument('isRecycleBinRestore')
           && ($singleEvent = $event->getObject())
           && $singleEvent instanceof SingleEvent) {
            $courseDate = $singleEvent->getCourseDate()->copy();
            $singleEvent->setWeekday($courseDate->format('l'));

            $courseTime = explode(':', $singleEvent->getCourseStartTime());
            $singleEvent->setStartTimestamp($courseDate
                                                ->setTimezone('Europe/Berlin')
                                                ->setHours($courseTime[0])
                                                ->setMinutes($courseTime[1])
                                                ->setTimezone('UTC')->timestamp);
        }
    }

    /**
     * @throws Exception
     */
    public function checkIfParentCourseIsPublished(DataObjectEvent $event): void
    {
        /** @var Course $parentCourse */
        if (false === $this->isCallFromAutoSave($event)
            && !$event->hasArgument('isRecycleBinRestore')
            && ($object = $event->getObject())
            && $object instanceof SingleEvent
            && $object->isPublished()
            && ($parentCourse = $this->dataObjectService->getRecursiveParent($object, Course::class))
                 && false === $parentCourse->isPublished()) {
                 throw new ObjectPublishingException(
                     '<b>' . $this->translator->trans('admin.object.event.message.cantPublish', [], 'admin') . '</b>'
                 );
         }
    }

    /**
     * @throws Exception
     */
    public function checkIfUnpublishable(DataObjectEvent $event): void
    {
        if(false === $this->isCallFromAutoSave($event)
           && !$event->hasArgument('isRecycleBinRestore')
           && ($singleEvent = $event->getObject())
           && $singleEvent instanceof SingleEvent
           && true === $this->singleEventService->checkIfThisIsAnUnpublishEvent($singleEvent)
           && false === $this->singleEventService->checkIfEventsMayUnpublish([$singleEvent])) {
                throw new ObjectPublishingException(
                    '<b>'.$this->translator->trans('admin.singleEvent.event.message.cantUnpublish', [], 'admin').'</b>'
                );
            }
    }

    /**
     * @throws ObjectSavingForbiddenException
     * @throws Exception
     */
    public function checkIfCancellable(DataObjectEvent $event): void
    {
        if(false === $this->isCallFromAutoSave($event)
           && ($singleEvent = $event->getObject())
           && $singleEvent instanceof SingleEvent
           && true === $this->singleEventService->checkIfThisIsACancelEvent($singleEvent)
           && false === $this->singleEventService->checkIfEventsMayCancel($singleEvent)) {
            throw new ObjectSavingForbiddenException(
                '<b>'.$this->translator->trans('admin.singleEvent.event.message.cantCancel', [], 'admin').'</b>'
            );
        }
    }

    public function modifyEventKey(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && !$event->hasArgument('isRecycleBinRestore')
            && ($object = $event->getObject())
            && $object instanceof SingleEvent
            && ($parentCourse = $this->dataObjectService->getRecursiveParent($object, Course::class))) {

            /** @var Course $parentCourse */
            $eventKey = $parentCourse->getCourseID() . '_' . $object->getId() . '__' . Carbon::createFromTimestamp(
                    $this->singleEventService->getEventDateTimeTimestamp($object)
                )->format('y-m-d');

            $object->setKey($eventKey);
        }
    }

    /**
     * @throws Exception
     */
    public function adjustAvailableCapacity(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && !$event->hasArgument('isRecycleBinRestore')
            && ($object = $event->getObject())
            && $object instanceof SingleEvent
            && $origObject = SingleEvent::getById($object->getId(), true)) {
            if ($origObject->getMaxCapacity() != null && $origObject->getMaxCapacity() != $object->getMaxCapacity()) {
                $difference = $origObject->getMaxCapacity() - $object->getMaxCapacity();
                $newCapacity = $object->getCapacity() - $difference;

                if ($newCapacity < 0) {
                    throw new NegativeCapacityException("New capacity is lower than currently booked");
                }
                $object->setCapacity($newCapacity);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function checkPartnerTerminationDate(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && !$event->hasArgument('isRecycleBinRestore')
            && ($object = $event->getObject())
            && $object instanceof SingleEvent
            && ($parentCourse = $object->getParentCourse())
            && ($partnerProfile = $parentCourse->getPartnerProfile())
            && $partnerProfile->getTerminationDate()) {
            if ($partnerProfile->getTerminationDate()->clone()->setTime(23,59,59)->timestamp < $object->getStartTimestamp()) {
                throw new ObjectSavingForbiddenException('<b>The event date lies beyond the partner\'s termination date.</b>');
            }

        }

    }


    private function isCallFromAutoSave(DataObjectEvent $event): bool
    {
        $arguments = $event->getArguments();
        if(is_array($arguments) && isset($arguments['isAutoSave'])) {
            return true;
        }
        return false;
    }

}
