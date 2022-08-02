<?php

declare(strict_types=1);

namespace Tests\DataTransformer\Faq;

use App\DataTransformer\Faq\FaqOutputDataTransformer;
use App\Dto\Faq\FaqDto;
use App\Service\I18NService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\DataObject\Faq;

final class FaqOutputDataTransformerTest extends TestCase
{
    protected I18NService|MockObject $i18NService;

    private FaqOutputDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->i18NService = $this->createMock(I18NService::class);

        $this->transformer = new FaqOutputDataTransformer();
        $this->transformer->setI18NService($this->i18NService);
    }

    protected function tearDown(): void
    {
        unset(
            $this->transformer,
            $this->i18NService,
        );
    }

    /**
     * @param mixed  $dto
     * @param string $to
     * @param bool   $supportResult
     *
     * @dataProvider dataProviderSupportTransformation
     */
    public function testSupportsTransformation($dto, string $to, bool $supportResult): void
    {
        $result = $this->transformer->supportsTransformation($dto, $to, []);
        self::assertSame($supportResult, $result);
    }

    public function dataProviderSupportTransformation(): iterable
    {
        yield [$this->createMock(Faq::class), FaqDto::class, true];
        yield [$this->createMock(Faq::class), \stdClass::class, false];
        yield [null, FaqDto::class, false];
    }

    public function testTransform(): void
    {
        $faq = $this->createMock(Faq::class);

        $language = 'en';
        $this->i18NService
            ->expects(self::once())
            ->method('getLanguageFromRequest')
            ->willReturn($language)
        ;

        $faq
            ->expects(self::once())
            ->method('getQuestion')
            ->with($language)
            ->willReturn('q')
        ;

        $faq
            ->expects(self::once())
            ->method('getAnswer')
            ->with($language)
            ->willReturn('a')
        ;

        $result = $this->transformer->transform($faq, FaqDto::class);
        self::assertSame('q', $result->question);
        self::assertSame('a', $result->answer);
    }
}
