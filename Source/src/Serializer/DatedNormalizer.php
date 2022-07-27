<?php

namespace App\Serializer;

use App\DataCollection\DatedPartnerListing;
use App\DataCollection\DatedCourseListing;
use App\Repository\CourseRepository;
use App\Repository\PartnerProfileRepository;
use App\Repository\SingleEventRepository;
use Pimcore\Model\DataObject;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DatedNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    private NormalizerInterface $normalizer;
    private PartnerProfileRepository $partnerProfileRepository;
    private CourseRepository $courseRepository;
    private SingleEventRepository $eventRepository;

    public function __construct(PartnerProfileRepository $partnerProfileRepository, CourseRepository $courseRepository,
                                SingleEventRepository $eventRepository)
    {
        $this->partnerProfileRepository = $partnerProfileRepository;
        $this->courseRepository = $courseRepository;
        $this->eventRepository = $eventRepository;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $format === 'json' && ($data instanceof DatedPartnerListing || $data instanceof DatedCourseListing)
            && isset($context['collection_operation_name']) && $context['collection_operation_name'] == 'get';
    }

    /**
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [];
        $data['lastUpdateTimestamp'] = 0;

        $data['data'] = [];
        /** @var DataObject $element */
        foreach ($object as $element) {
            if ($element->getModificationDate() > $data['lastUpdateTimestamp']) {
                $data['lastUpdateTimestamp'] = $element->getModificationDate();
            }
            $data['data'][] = $this->normalizer->normalize($element, $format, $context);
        }

        if ($data['lastUpdateTimestamp'] == 0) {
            if ($object instanceof DatedPartnerListing) {
                $data['lastUpdateTimestamp'] = $this->partnerProfileRepository->getLastModifiedAndPublishedPartner()?->getModificationDate();
            }
            elseif ($object instanceof DatedCourseListing) {
                $courseModificationDate = $this->courseRepository->getLastModifiedCourse()?->getModificationDate();
                $eventModificationDate = $this->eventRepository->getLastModifiedAndPublishedEvent()?->getModificationDate();
                $data['lastUpdateTimestamp'] = ($eventModificationDate == null || $courseModificationDate > $eventModificationDate)  ? $courseModificationDate : $eventModificationDate;
            }
        }

        return [$data];
    }

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }
}
