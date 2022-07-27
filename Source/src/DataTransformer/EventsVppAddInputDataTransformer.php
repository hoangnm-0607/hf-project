<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataCollection\EventErrorCollection;
use App\Dto\VPP\Events\EventAddInputDto;
use App\Entity\Courses;
use App\Entity\PartnerProfile;
use App\Entity\SingleEvent;
use App\Exception\InvalidPartnerException;
use App\Exception\ObjectNotFoundException;
use App\Repository\CourseRepository;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\CourseGeneratorService;
use App\Service\PartnerProfileService;
use Carbon\Carbon;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class EventsVppAddInputDataTransformer implements DataTransformerInterface
{
    private RequestStack $requestStack;
    private InMemoryUserValidator $inMemoryUserValidator;
    private ValidatorInterface $validator;
    private CourseGeneratorService $courseGeneratorService;
    private CourseRepository $courseRepository;
    private PartnerProfileService $partnerProfileService;

    public function __construct(RequestStack $requestStack, InMemoryUserValidator $inMemoryUserValidator,
                                ValidatorInterface $validator, CourseGeneratorService $courseGeneratorService,
                                CourseRepository $courseRepository, PartnerProfileService $partnerProfileService)
    {
        $this->requestStack = $requestStack;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->validator = $validator;
        $this->courseGeneratorService = $courseGeneratorService;
        $this->courseRepository = $courseRepository;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param EventAddInputDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : EventErrorCollection
    {
        $this->validator->validate($object);

        $casPublicId = $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId();
        $partnerProfile = PartnerProfile::getByCASPublicID($casPublicId,1);
        $this->partnerProfileService->checkIfChangesAreAllowed($partnerProfile);
        $this->partnerProfileService->checkIfCourseManagerIsActive($partnerProfile);

        $courseId = $this->requestStack->getCurrentRequest()->attributes->get('courseId');
        $course = Courses::getById($courseId);

        if (!$course) {
            throw new ObjectNotFoundException('Courses with ID ' . $courseId . ' not found');
        }

        if(!str_starts_with($course->getFullPath(), $partnerProfile->getFullPath())) {
            throw new InvalidPartnerException('partnerId does not match courseId');
        }

        $startDate = new Carbon($object->appointments->startDate, $object->appointments->timeZone);
        $endDate = $object->appointments->seriesSettings?->endDate ? new Carbon($object->appointments->seriesSettings->endDate, $object->appointments->timeZone) : null;


        $dateList = $this->courseGeneratorService->getEventSeriesDates(
            $object->appointments->seriesSettings?->repetitions,
            $object->appointments->seriesSettings?->weekdays,
            $startDate,
            $endDate,
        );

        $localTime = new Carbon($object->appointments->startDate . ' ' . $object->appointments->time, $object->appointments->timeZone);
        $localTime->setTimezone(date_default_timezone_get());

        $eventErrorCollection = $this->courseGeneratorService->generateNewEventCollection(
            $dateList,
            $course,
            [
                'startTime' => $localTime->format('H:i'),
                'duration' => $object->appointments->duration,
                'capacity' => $object->appointments->capacity,
                'enteredTimeZone' => $object->appointments->timeZone,
            ],
            [
                'streamingHost' => $object->streamSettings->streamingHost,
                'meetingId' => $object->streamSettings->meetingId,
                'additionalInformation' => $object->streamSettings->additionalInformation,
                'streamPassword' => $object->streamSettings->streamPassword,
                'streamLink' => $object->streamSettings->streamLink,
            ],
            $object->published
        );

        $oldEventCollection = $this->courseRepository->getUnpublishedSingleEvents($course);

        $course->setSingleEvents(array_merge($oldEventCollection, iterator_to_array($eventErrorCollection->getEvents())));
        if ($object->published && !$course->isPublished() && $eventErrorCollection->getEvents()->count() > 0) {
            $course->setPublished(true);
        }
        $course->save();

        return $eventErrorCollection;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof SingleEvent) {
            return false;
        }

        return $to === SingleEvent::class && (
                ($data instanceof EventAddInputDto) ||
                ($context['input']['class'] ?? null) === EventAddInputDto::class
            );
    }
}
