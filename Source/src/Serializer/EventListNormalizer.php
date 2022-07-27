<?php

namespace App\Serializer;

use App\DataCollection\EventList;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EventListNormalizer extends PaginationNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    private NormalizerInterface $normalizer;

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $format === 'json' && $data instanceof EventList;
    }

    /**
     * @param EventList $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [];
        $eventsIterator = $object->getEvents();
        $data['pagination'] = $this->normalizePagination($eventsIterator);

        $data['result'] = [];
        foreach ($object as $element) {
            $data['result'][] = $this->normalizer->normalize($element, $format, $context);
        }

        $data['availableFilters'] = $object->getAvailableFilterValues() ?? [];

        return $data;
    }

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }
}
