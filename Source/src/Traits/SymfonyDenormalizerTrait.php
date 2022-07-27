<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Service\Attribute\Required;

trait SymfonyDenormalizerTrait
{
    protected DenormalizerInterface|Serializer $symfonyNormalizer;

    #[Required]
    public function setSymfonyDenormalizer(DenormalizerInterface $symfonyNormalizer): void
    {
        $this->symfonyNormalizer = $symfonyNormalizer;
    }
}
