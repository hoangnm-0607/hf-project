<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Events\EventCancelInputDto;
use App\Entity\Courses;
use App\Entity\SingleEvent;
use App\Exception\InvalidPartnerException;
use App\Security\Validator\InMemoryUserValidator;
use App\Service\PartnerProfileService;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EventsVppCancelInputDataTransformer implements DataTransformerInterface
{
    private RequestStack $requestStack;
    private InMemoryUserValidator $inMemoryUserValidator;
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(RequestStack $requestStack, InMemoryUserValidator $inMemoryUserValidator,
                                ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->requestStack = $requestStack;
        $this->inMemoryUserValidator = $inMemoryUserValidator;
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }

    /**
     * @param EventCancelInputDto $object
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : SingleEvent
    {
        $this->validator->validate($object);

        $casPublicId = $this->requestStack->getCurrentRequest()->attributes->get('publicId');
        $this->inMemoryUserValidator->validateTokenAndAccessToRequestedEntityId($casPublicId);

        $courseId = $this->requestStack->getCurrentRequest()->attributes->get('courseId');
        $course = Courses::getById($courseId);

        if(($profile = $course->getPartnerProfile()) && $profile->getCasPublicId() != $casPublicId) {
            throw new InvalidPartnerException('partnerId does not match courseId');
        }

        $this->partnerProfileService->checkIfCourseManagerIsActive($profile);

        /** @var SingleEvent $event */
        $event = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        if ($object->cancel) {
            $event->setCancelled(true);
            $event->save();
        }

        return $event;
    }


    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof SingleEvent) {
            return false;
        }

        return $to === SingleEvent::class && (
                ($data instanceof EventCancelInputDto) ||
                ($context['input']['class'] ?? null) === EventCancelInputDto::class
            );
    }
}
