<?php


namespace App\Service;

use Aws\ApiGateway\ApiGatewayClient;
use Aws\Sdk;


class ApiGatewayService
{
    private ApiGatewayClient $apiGatewayClient;

    public const STAGE_PROD = 'prod';


    public function __construct()
    {
        $awsParams = [
            'region'  => $_ENV['AWS_REGION'],
            'version' => 'latest',
        ];

        if (isset($_ENV['AWS_ACCESS_KEY_ID']) && isset($_ENV['AWS_SECRET_ACCESS_KEY'])) {
            $awsParams['credentials']['key'] = $_ENV['AWS_ACCESS_KEY_ID'];
            $awsParams['credentials']['secret'] = $_ENV['AWS_SECRET_ACCESS_KEY'];
        }

        $sdk = new Sdk($awsParams);
        $this->apiGatewayClient = $sdk->createApiGateway();
    }


    public function flushStage(string $restApiId, string $stageName): void
    {
        $this->apiGatewayClient->flushStageCache([
            'restApiId' => $restApiId,
            'stageName' => $stageName,
        ]);
    }
}
