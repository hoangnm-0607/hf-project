<?php

declare(strict_types=1);

namespace App\Service\CAS;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CasHttpClientFactory
{
    private string $environment;
    private string $casApiPlatformUri;
    private string $casSystemApiUser;
    private string $casSystemApiPassword;

    public function __construct(string $environment, string $casApiPlatformUri, string $casSystemApiUser, string $casSystemApiPassword)
    {
        $this->environment = $environment;
        $this->casApiPlatformUri = $casApiPlatformUri;
        $this->casSystemApiUser = $casSystemApiUser;
        $this->casSystemApiPassword = $casSystemApiPassword;
    }

    public function create(): HttpClientInterface
    {
        $options = [
            'auth_basic' => [$this->casSystemApiUser, $this->casSystemApiPassword],
        ];

        if ('prod' !== $this->environment) {
            $options = array_merge($options, ['verify_peer' => false, 'verify_host' => false]);
        }

        return HttpClient::createForBaseUri($this->casApiPlatformUri, $options);
    }
}
