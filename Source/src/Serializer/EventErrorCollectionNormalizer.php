<?php

namespace App\Serializer;

use App\DataCollection\EventErrorCollection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EventErrorCollectionNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    private NormalizerInterface $normalizer;

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $format === 'json' && $data instanceof EventErrorCollection;
    }

    /**
     * @param EventErrorCollection $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {

        $data['errors'] = $object->getErrors();
        $data['events'] = $this->normalizer->normalize($object->getEvents(), $format ,$context);

        return $data;
    }

    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }
}
