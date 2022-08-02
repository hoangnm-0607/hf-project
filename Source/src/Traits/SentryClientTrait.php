<?php

declare(strict_types=1);

namespace App\Traits;

use Sentry\ClientInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SentryClientTrait
{
    protected ClientInterface $sentryClient;

    #[Required]
    public function setSentryClient(ClientInterface $sentryClient): void
    {
        $this->sentryClient = $sentryClient;
    }
}
