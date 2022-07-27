<?php

declare(strict_types=1);

namespace App\Serializer;

use ApiPlatform\Core\Serializer\ItemNormalizer;
use App\Helper\ConstHelper;
use Pimcore\Model\Asset\Text;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

class AssetNormalizer implements ContextAwareNormalizerInterface
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

        return 'json' === $format
            && $data instanceOf Text
            && isset($context['collection_operation_name'])
            && 'get-bulk-upload-list'.ConstHelper::AS_MANAGER === $context['collection_operation_name']
        ;
    }

    public function normalize($object, string $format = null, array $context = []): string|int|bool|\ArrayObject|array|null|float
    {
        return $this->normalizer->normalize($object, $format, $context);
    }
}
