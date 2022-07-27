<?php


namespace App\Service;



use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatorService
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getTranslatedValues(?array $values, string $language): array
    {
        return array_map(function($value) use ($language) {
            return $this->translator->trans($value, [], 'admin', $language);
        }, $values);
    }

    public function getAdminTrans(string $key, string $language): ?string
    {
        return $this->translator->trans($key, [], 'admin', $language);
    }

}
