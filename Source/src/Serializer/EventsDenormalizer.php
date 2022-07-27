<?php

namespace App\Serializer;

use App\Dto\VPP\Events\EventInputDto;

use ArrayIterator;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EventsDenormalizer implements ContextAwareDenormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === EventInputDto::class;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): iterable
    {
        $result = [];
        foreach ($data as $event) {
            $result[] = $this->normalizer->denormalize($event, $type, $format,$context);
        }
        return new ArrayIterator($result);
    }
}
