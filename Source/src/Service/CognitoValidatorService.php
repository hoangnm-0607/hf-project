<?php

namespace App\Service;

use App\Traits\RequestStackTrait;
use Carbon\Carbon;
use Exception;

class CognitoValidatorService
{
    use RequestStackTrait;

    private string $awsCognitoJwksVppLocalKey;
    private string $awsCognitoJwksLocalKey;
    private string $awsCognitoJwksCcpLocalKey;
    private string $awsUserPoolPartner;
    private string $awsUserPoolExcercising;
    private string $awsUserPoolCompany;

    public function __construct(string $awsCognitoJwksVppLocalKey, string $awsCognitoJwksLocalKey, string $awsCognitoJwksCcpLocalKey, string $awsUserPoolPartner, string $awsUserPoolExcercising, string $awsUserPoolCompany)
    {
        $this->awsCognitoJwksVppLocalKey = $awsCognitoJwksVppLocalKey;
        $this->awsCognitoJwksLocalKey = $awsCognitoJwksLocalKey;
        $this->awsCognitoJwksCcpLocalKey = $awsCognitoJwksCcpLocalKey;
        $this->awsUserPoolPartner = $awsUserPoolPartner;
        $this->awsUserPoolExcercising = $awsUserPoolExcercising;
        $this->awsUserPoolCompany = $awsUserPoolCompany;
    }

    /**
     * @throws Exception
     */
    public function getUserPoolKeys(): string
    {
        $firewallContext = $this->requestStack->getCurrentRequest()->attributes->get('_firewall_context');

        $localKeyPath = match ($firewallContext) {
            'security.firewall.map.context.api_vpp' => $this->awsCognitoJwksVppLocalKey,
            'security.firewall.map.context.api_ccp' => $this->awsCognitoJwksCcpLocalKey,
            default => $this->awsCognitoJwksLocalKey,
        };

        if (!file_exists($localKeyPath) || filemtime($localKeyPath) < Carbon::now()->subDays(30)->timestamp) {
            $this->fetchCognitoKeyFile($localKeyPath);
        }

        return file_get_contents($localKeyPath);
    }

    /**
     * @throws Exception
     */
    private function fetchCognitoKeyFile(string $localKeyPath): void
    {
        $firewallContext = $this->requestStack->getCurrentRequest()->attributes->get('_firewall_context');

        $awsPool = match ($firewallContext) {
            'security.firewall.map.context.api_vpp' => $this->awsUserPoolPartner,
            'security.firewall.map.context.api_ccp' => $this->awsUserPoolCompany,
            default => $this->awsUserPoolExcercising,
        };

        $keyUri = sprintf('https://cognito-idp.eu-central-1.amazonaws.com/%s/.well-known/jwks.json', $awsPool);

        if (false === file_put_contents($localKeyPath, fopen($keyUri, 'r'))) {
            throw new Exception('Couldn\'t fetch jwks file from AWS Cognito.');
        }
    }
}
