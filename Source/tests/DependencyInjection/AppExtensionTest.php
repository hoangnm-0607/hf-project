<?php declare(strict_types=1);

namespace Tests\DependencyInjection;

use App\DependencyInjection\AppExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AppExtensionTest extends TestCase
{
    private AppExtension $appExtension;

    protected function setUp(): void
    {
        $this->appExtension = new AppExtension();
    }

    public function testLoadAppExtension(): void
    {
        $containerMock = $this->createMock(ContainerBuilder::class);
        $parameterBagMock = $this->createMock(ParameterBagInterface::class);

        $parameterBagMock
            ->expects(self::atLeast(24))
            ->method('resolveValue')
        ;

        $parameterBagMock
            ->expects(self::exactly(26))
            ->method('unescapeValue')
            ->willReturnOnConsecutiveCalls(
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml',
                [], 'services.yml'
            );

        $containerMock->method('getParameterBag')->willReturn($parameterBagMock);

        $this->appExtension->load([], $containerMock);
    }
}
