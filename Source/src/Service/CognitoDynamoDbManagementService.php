<?php


namespace App\Service;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk;


class CognitoDynamoDbManagementService
{
    private DynamoDbClient $dynamoDbClient;

    public const CCP_TABLE_NAME = 'cognito_ccp_key_value_store';
    public const CCP_INDEX_NAME = 'companyId-cognitoId-index';
    public const CCP_KEY = 'companyId';

    public const CCP_ADMIN_TABLE_NAME = 'ccp_admin_invitation';
    public const CCP_ADMIN_INDEX_NAME = 'ccp_admin_invitation';
    public const CCP_ADMIN_KEY = 'companyId';

    public const VPP_TABLE_NAME = 'cognito_vpp_key_value_store';
    public const VPP_INDEX_NAME = 'publicId-cognitoId-index';
    public const VPP_KEY = 'publicId';


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
        $this->dynamoDbClient = $sdk->createDynamoDb();
    }

    public function getAllRowsById(string $id, string $key, string $tableName, ?string $indexName = null): array
    {
        $marshaler = new Marshaler();

        $parameters = [
            'TableName' => $tableName,
            'KeyConditionExpression' => $key . ' = :id',
            'ExpressionAttributeValues'=> $marshaler->marshalItem([":id" => $id])
        ];

        if ($indexName != null) {
            $parameters['IndexName'] = $indexName;
        }

        $result = $this->dynamoDbClient->query($parameters);

        $users = [];
        foreach ($result['Items'] as $cognitoUser) {
            $users[] = $marshaler->unmarshalItem($cognitoUser);
        }

        return $users;
    }


    public function getCognitoUsersByPublicId(string $publicId): array
    {
        $marshaler = new Marshaler();

        $result = $this->dynamoDbClient->query([
            'TableName' => 'cognito_vpp_key_value_store',
            'IndexName' => 'publicId-cognitoId-index',
            'KeyConditionExpression' => 'publicId = :publicId',
            'ExpressionAttributeValues'=> $marshaler->marshalItem([":publicId" => $publicId])
        ]);

        $users = [];
        foreach ($result['Items'] as $cognitoUser) {
            $users[] = $marshaler->unmarshalItem($cognitoUser);
        }

        return $users;
    }

    public function getActiveCognitoUsersByPublicId(string $publicId): array
    {
        $filtered = [];
        foreach ($this->getCognitoUsersByPublicId($publicId) as $user) {
            if ($user['status'] == 'Active') {
                $filtered[] = $user;
            }
        }

        return $filtered;
    }

    public function deactivateUsersByPublicId(string $publicId): array {
        $marshaler = new Marshaler();

        $users = $this->getCognitoUsersByPublicId($publicId);

        $deactivatedUsers = [];
        foreach ($users as $user) {
            if ($user['status'] != 'Blocked') {
                $this->dynamoDbClient->updateItem([
                    'TableName' => 'cognito_vpp_key_value_store',
                    'Key' => $marshaler->marshalItem([
                        "cognitoId" => $user['cognitoId'],
                        "publicId" => $user['publicId'],
                    ]),
                    'UpdateExpression' => 'set #status = :status',
                    'ExpressionAttributeValues'=> $marshaler->marshalItem([":status" => 'Blocked']),
                    'ExpressionAttributeNames'=> ["#status" => 'status'],
                    'ReturnValues' => 'UPDATED_NEW'
                ]);
                $deactivatedUsers[] = $user['cognitoId'];
            }
        }

        return $deactivatedUsers;
    }
}
