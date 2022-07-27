<?php

namespace App\Service;

use App\Helper\ConstHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class I18NService
{
    private string $defaultLocale;
    private array $locales;

    public function __construct(string $defaultLocale, array $locales)
    {
        $this->defaultLocale = $defaultLocale;
        $this->locales = $locales;
    }

    public function getLanguageFromRequest(): string
    {
        $request = Request::createFromGlobals();

        if ($request->query->has(ConstHelper::LANGUAGE_PARAM_NAME)) {
            $language = $request->get(ConstHelper::LANGUAGE_PARAM_NAME, $this->defaultLocale);
        } else {
            $language = $request->getPreferredLanguage($this->locales);
        }

        if (ConstHelper::LANGUAGE_SUPPORT !== $this->locales) {
            throw new \LogicException('Need to Fix language support array!');
        }

        $language = strtolower($language);

        if (!in_array($language, $this->locales, true)) {
            throw new BadRequestHttpException(sprintf('Unsupported language:%s', $language));
        }

        return $language;
    }
}
