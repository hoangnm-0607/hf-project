<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DataCollection\EventErrorCollection;
use App\Dto\VPP\Events\EventInputDto;
use App\Entity\PartnerProfile;
use App\Entity\SingleEvent;
use App\Exception\AlreadyBookedException;
use App\Exception\InvalidPartnerException;
use App\Exception\ObjectNotFoundException;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\PartnerProfileService;
use ArrayIterator;
use Carbon\Carbon;
use Exception;
use Pimcore\Model\DataObject\Course;

class EventsVppUpdateInputDataTransformer implements DataTransformerInterface
{
    private InMemoryUserValidator $inMemoryUserValidator;
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(InMemoryUserValidator $inMemoryUserValidator, ValidatorInterface $validator,
                                PartnerProfileService $partnerProfileService)
    {
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param EventInputDto[] $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : EventErrorCollection
    {
        foreach ($object as $eventDto) {
            $this->validator->validate($eventDto);
        }

        $casPublicId = $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId();
        $partnerProfile = PartnerProfile::getByCASPublicID($casPublicId, 1);
        $this->partnerProfileService->checkIfChangesAreAllowed($partnerProfile);
        $this->partnerProfileService->checkIfCourseManagerIsActive($partnerProfile);


        $eventCollection = new ArrayIterator();
        $errors = [];
        foreach ($object as $eventDto) {
            $event = SingleEvent::getById($eventDto->eventId);
            if (!$event) {
                throw new ObjectNotFoundException('Event with id ' . $eventDto->eventId . ' not found');
            }
            if(!str_starts_with($event->getFullPath(), $partnerProfile->getFullPath())) {
                throw new InvalidPartnerException('partnerId does not match eventId');
            }

            try {
                $this->setEventSettings($eventDto, $event);
                $this->setStreamSettings($eventDto, $event);
                $event->save();
                $eventCollection->append($event);
            } catch (Exception $e) {
                $errors[$eventDto->eventId] = $e->getMessage();
            }
        }

        return new EventErrorCollection($eventCollection, $errors);
    }

    /**
     * @throws AlreadyBookedException
     * @throws Exception
     */
    private function setEventSettings(EventInputDto $dto, SingleEvent $event) {
        if (isset($dto->date)) {
            $date = new Carbon($dto->date, $dto->timeZone ?? date_default_timezone_get());
            $event->setCourseDate($date);
        }
        if (isset($dto->time)) {
            $event->setCourseStartTime($dto->time);
        }
        if (isset($dto->capacity)) {
            $event->setMaxCapacity($dto->capacity);
        }
        if (isset($dto->duration)) {
            $event->setDuration($dto->duration);
        }
        if (isset($dto->cancelled)) {
            if ($dto->cancelled && $event->getBookings()){
                throw new AlreadyBookedException("Event has bookings");
            } else {
                $event->setCancelled($dto->cancelled);
            }
        }
        if (isset($dto->published)) {
            Course::setHideUnpublished(false);
            $course = $event->getParentCourse();
            Course::setHideUnpublished(true);
            if (!$course->isPublished()) {
                $course->setPublished(true);
                $course->save();
            }

            $event->setPublished($dto->published);
        }
    }

    private function setStreamSettings(EventInputDto $dto, SingleEvent $event) {
        if (isset($dto->streamingHost)) {
            $event->setStreamingHost($dto->streamingHost);
        }
        if (isset($dto->streamLink)) {
            $event->setStreamLink($dto->streamLink);
        }
        if (isset($dto->streamPassword)) {
            $event->setStreamPassword($dto->streamPassword);
        }
        if (isset($dto->additionalInformation)) {
            $event->setAdditionalInformation($dto->additionalInformation);
        }
        if (isset($dto->meetingId)) {
            $event->setMeetingId($dto->meetingId);
        }
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof SingleEvent) {
            return false;
        }

        return $to === SingleEvent::class && (
                ($data instanceof EventInputDto) ||
                ($context['input']['class'] ?? null) === EventInputDto::class
            );
    }
}
