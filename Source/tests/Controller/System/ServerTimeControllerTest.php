<?php

declare(strict_types=1);

namespace Tests\Controller\System;

use App\Controller\System\ServerTimeController;
use App\Dto\System\ServerTimeDto;
use PHPUnit\Framework\TestCase;

final class ServerTimeControllerTest extends TestCase
{
    public function testInvoke(): void
    {
        $controller = new ServerTimeController();

        $result = $controller->__invoke();
        self::assertInstanceOf(ServerTimeDto::class, $result);
    }
}
