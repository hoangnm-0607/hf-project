<?php

use App\AppBundle;
use Kreait\Firebase\Symfony\Bundle\FirebaseBundle;

return [
    AppBundle::class => ['all' => true],
    FirebaseBundle::class => ['all' => true],
    Sentry\SentryBundle\SentryBundle::class => ['all' => true],
];
