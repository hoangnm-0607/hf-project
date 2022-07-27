<?php declare(strict_types=1);

use Pimcore\Bootstrap;

include dirname(__DIR__)."/vendor/autoload.php";

DG\BypassFinals::enable();

Bootstrap::setProjectRoot();
try {
    Bootstrap::bootstrap();
} catch (Exception $e) {
}

$kernel = Bootstrap::kernel();
$kernel->boot();
