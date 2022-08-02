<?php

declare(strict_types=1);

namespace App\Serializer;

use ApiPlatform\Core\Serializer\ItemNormalizer;
use Pimcore\Model\DataObject\Faq;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class FaqNormalizer implements ContextAwareNormalizerInterface
{
    private ItemNormalizer $normalizer;

    public function setNormalizer(ItemNormalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        if (!\is_object($data)) {
            return false;
        }

        return 'json' === $format && $data instanceOf Faq;
    }

    public function normalize($object, string $format = null, array $context = []): string|int|bool|\ArrayObject|array|null|float
    {
        return $this->normalizer->normalize($object, $format, $context);
    }
}
