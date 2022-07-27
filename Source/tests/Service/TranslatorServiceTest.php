<?php

namespace Tests\Service;

use App\Service\TranslatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Translator;

class TranslatorServiceTest extends TestCase
{

    protected Translator $translator;

    public function setUp(): void
    {
        $this->translator = new Translator('de');
    }

    /**
     * @test
     */
    public function GetTranslatedValues_ReturnsEqualNotTranslatedValues()
    {
        $translatorService = new TranslatorService($this->translator);
        $translatable = [
            'fakedVal1',
            'fakedVal2',
            'fakedVal3',
        ];
        $output = $translatorService->getTranslatedValues($translatable, 'de');
        $expected = $translatable;
        self::assertEquals($expected, $output);
    }

    /**
     * @test
     */
    public function GetTranslatedValues_ReturnsNotEqualNotTranslatedValues()
    {
        $translatorService = new TranslatorService($this->translator);
        $translatable = [
            'fakedVal1',
            'fakedVal2',
            'fakedVal3',
        ];
        $output = $translatorService->getTranslatedValues($translatable, 'de');
        $expected = [
            'notFakedVal1',
            'notFakedVal2',
            'notFakedVal3',
        ];
        self::assertNotEquals($expected, $output);
    }
}
