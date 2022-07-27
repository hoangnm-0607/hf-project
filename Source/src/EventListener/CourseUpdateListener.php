<?php


namespace App\EventListener;


use App\Exception\ObjectPublishingException;
use App\Exception\ObjectSavingForbiddenException;
use App\Repository\CourseRepository;
use App\Service\DataObjectService;
use App\Service\SingleEventService;
use Exception;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\PartnerProfile;
use Symfony\Contracts\Translation\TranslatorInterface;

class CourseUpdateListener
{

    private SingleEventService $singleEventService;
    private TranslatorInterface $translator;
    private CourseRepository $courseRepository;
    private DataObjectService $dataObjectService;

    public function __construct(
        SingleEventService $singleEventService,
        TranslatorInterface $translator,
        CourseRepository $courseRepository,
        DataObjectService $dataObjectService
    ) {
        $this->singleEventService = $singleEventService;
        $this->translator         = $translator;
        $this->courseRepository   = $courseRepository;
        $this->dataObjectService = $dataObjectService;
    }

    /**
     * @throws Exception
     */
    public function unpublishEventsWhenCourseIsUnpublished(DataObjectEvent $event): void
    {
        if (($course = $event->getObject()) && $course instanceof Course
            && false === $course->isPublished()
            && ($singleEvents = $course->getSingleEvents())) {
            if ($this->singleEventService->checkIfEventsMayUnpublish($singleEvents)) {
                foreach ($singleEvents as $singleEvent) {
                    $singleEvent->setPublished(false);
                    $singleEvent->save();
                }
            } else {
                throw new ObjectPublishingException(
                    '<b><h3>' . $course->getCourseName() . '</h3>' . $this->translator->trans('admin.object.course.message.cantUnpublish', [], 'admin') . '</b>'
                );
            }
        }
    }

    /**
     * @throws ObjectSavingForbiddenException
     * @throws Exception
     */
    public function checkIfCoursesMayBeRenamed(DataObjectEvent $event): void
    {
        if (($course = $event->getObject()) && $course instanceof Course && $singleEvents = $course->getSingleEvents()) {

            // get original course object
            $preUpdateCourse = $this->courseRepository->getOneCourseById($course->getId());
            // check, if object key has changed
            if($course->getKey() !== $preUpdateCourse->getKey()) {
                foreach ($singleEvents as $singleEvent) {
                    if ($this->singleEventService->getEventDateTimeTimestamp($singleEvent)
                        && $singleEvent->getBookings()) {
                        // don't translate
                        throw new ObjectSavingForbiddenException(
                            'admin.course.course.message.cantRename'
                        );
                    }
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    public function setPartnerProfile(DataObjectEvent $event): void
    {
        if (($course = $event->getObject())
            && $course instanceof Course
            && !$this->getUnpublishedPartnerProfile($course)
            && ($partnerProfile = $this->dataObjectService->getRecursiveParent($course, PartnerProfile::class))) {

                /** @var PartnerProfile $partnerProfile */
                $course->setPartnerProfile($partnerProfile);
                $course->save();
            }
    }

    /**
     * @throws Exception
     */
    public function checkIfParentPartnerProfileIsPublished(DataObjectEvent $event): void
    {
        if (false === $this->isCallFromAutoSave($event)
            && ! $event->hasArgument('isRecycleBinRestore')
            && ($course = $event->getObject())
            && $course instanceof Course
            && ($partnerProfile = $course->getPartnerProfile())
            && $course->getPublished()
            && false === $partnerProfile->getPublished())
        {
            throw new ObjectPublishingException(
                '<b>' . $this->translator->trans('admin.object.course.message.cantPublish', [], 'admin') . '</b>'
            );
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

    private function getUnpublishedPartnerProfile(Course $course): ?PartnerProfile
    {
        PartnerProfile::setHideUnpublished(false);
        $partnerProfile = $course->getPartnerProfile();
        PartnerProfile::setHideUnpublished(true);

        return $partnerProfile;

    }
}
