<?php

namespace App\Serializer;

use Pimcore\Model\DataObject\Course;
use Pimcore\Model\DataObject\Listing\Concrete;
use Pimcore\Model\DataObject\SingleEvent;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    private NormalizerInterface $normalizer;

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $format === 'json' && ($data instanceof Course\Listing || $data instanceof SingleEvent\Listing)
            && isset($context['collection_operation_name'])
            && in_array($context['collection_operation_name'], ['get_collection', 'get_events', 'get_events_archive']);
    }

    /**
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [];
        $data['pagination'] = $this->normalizePagination($object);

        $data['result'] = [];
        foreach ($object as $element) {
            $data['result'][] = $this->normalizer->normalize($element, $format, $context);
        }

        return $data;
    }

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalizePagination(Concrete $iterator): ?array {

        $pagination = null;
        if ($iterator->getLimit()) {
            $pagination['totalCount'] = $iterator->getTotalCount();
            $pagination['itemsPerPage'] = $iterator->getLimit();
            $pagination['pages'] = ceil($iterator->getTotalCount()/$iterator->getLimit());
            $pagination['currentPage'] = ($iterator->getOffset()/$iterator->getLimit()) + 1;
        }

        return $pagination;
    }
}
