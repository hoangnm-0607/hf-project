<?php

declare(strict_types=1);

namespace Tests;

use App\AppBundle;
use PHPUnit\Framework\TestCase;

final class AppBundleTest extends TestCase
{
    public function testConstructor(): void
    {
        $bundle = new AppBundle();

        $jsPaths = $bundle->getJsPaths();
        $cssPaths = $bundle->getCssPaths();

        self::assertCount(7, $jsPaths);
        self::assertCount(1, $cssPaths);
    }
}
