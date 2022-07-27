<?php


namespace App\Service;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\Sdk;


class CognitoService
{
    private CognitoIdentityProviderClient $cognitoClient;


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
        $this->cognitoClient = $sdk->createCognitoIdentityProvider();
    }


    public function getCognitoUserByEmail(string $email, string $userPoolId): ?array
    {
        $result = $this->cognitoClient->adminGetUser([
            'UserPoolId' => $userPoolId,
            'Username' => $email,
        ]);

        $user = null;
        foreach ($result->get('UserAttributes') as $attribute) {
            if ($attribute['Name'] == 'given_name') {
                $user['firstname'] = $attribute['Value'];
            }
            elseif($attribute['Name'] == 'name') {
                $user['lastname'] = $attribute['Value'];
            }
        }

        return $user;
    }
}
