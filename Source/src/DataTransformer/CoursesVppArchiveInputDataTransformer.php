<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Dto\VPP\Courses\CoursesVppArchiveInputDto;
use App\Entity\Courses;
use App\Exception\ExistingEventException;
use App\Service\ArchiveService;
use App\Service\PartnerProfileService;
use Carbon\Carbon;
use Exception;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class CoursesVppArchiveInputDataTransformer implements DataTransformerInterface
{
    private ArchiveService $archiveService;
    private ValidatorInterface $validator;
    private PartnerProfileService $partnerProfileService;

    public function __construct(ArchiveService $archiveService, ValidatorInterface $validator, PartnerProfileService $partnerProfileService)
    {
        $this->archiveService = $archiveService;
        $this->validator = $validator;
        $this->partnerProfileService = $partnerProfileService;
    }


    /**
     * @throws Exception
     */
    public function transform($object, string $to, array $context = []) : Courses
    {
        $this->validator->validate($object);

        /** @var Courses $course */
        $course = $context[AbstractNormalizer::OBJECT_TO_POPULATE];

        $this->partnerProfileService->checkIfCourseManagerIsActive($course->getPartnerProfile());

        if ($events = $course->getSingleEvents()) {
            foreach ($events as $event) {
                if ($event->getCourseDate() > new Carbon() && !$event->getCancelled()) {
                    throw new ExistingEventException("Active future event found with id " . $event->getId());
                }
            }
        }

        $this->archiveService->archiveCourseOrEvent($course);

        return $course;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $to === Courses::class && (
                ($data instanceof CoursesVppArchiveInputDto) ||
                ($context['input']['class'] ?? null) === CoursesVppArchiveInputDto::class
            );
    }
}
