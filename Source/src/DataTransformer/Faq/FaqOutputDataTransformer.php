<?php

declare(strict_types=1);

namespace App\DataTransformer\Faq;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Faq\FaqDto;
use App\Traits\I18NServiceTrait;
use Pimcore\Model\DataObject\Faq;

class FaqOutputDataTransformer implements DataTransformerInterface
{
    use I18NServiceTrait;

    /**
     * @param Faq     $object
     * @param string  $to
     * @param array   $context
     *
     * @return FaqDto
     */
    public function transform($object, string $to, array $context = []): FaqDto
    {
        $language = $this->i18NService->getLanguageFromRequest();

        $target = new FaqDto();

        $target->question = $object->getQuestion($language);
        $target->answer = $object->getAnswer($language);

        return $target;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceOf Faq && FaqDto::class === $to;
    }
}
