<?php

namespace App\Service;

use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Aws\DynamoDb\Exception\DynamoDbException;
use Pimcore\Model\DataObject\ClassDefinition\Layout\DynamicTextLabelInterface;
use Pimcore\Model\DataObject\PartnerProfile;

class CognitoUsersTextService implements DynamicTextLabelInterface
{
    /**
     * @param PartnerProfile $object
     */
    public function renderLayoutText($data, $object, $params): string
    {
        $text = '';

        if ($object?->getCASPublicID()) {
            try {
                $cognitoDynamoDbService = new CognitoDynamoDbManagementService();
                $result = $cognitoDynamoDbService->getAllRowsById(
                    $object->getCASPublicID(),
                    CognitoDynamoDbManagementService::VPP_KEY,
                    CognitoDynamoDbManagementService::VPP_TABLE_NAME,
                    CognitoDynamoDbManagementService::VPP_INDEX_NAME,
                );
            } catch (DynamoDbException $e) {
                return $e->getMessage();
            }

            $cognitoService = new CognitoService();

            $rows = [];
            foreach ($result as $cognitoDbUser) {
                try {
                    $cognitoUser = $cognitoService->getCognitoUserByEmail($cognitoDbUser['email'], $_ENV['USERPOOL_PARTNER']);
                } catch (CognitoIdentityProviderException $e) {
                    $cognitoUser = [
                        'firstname' => 'Cognitouser',
                        'lastname' => 'not found'
                    ];
                }
                $rows[] = [
                    'firstname' => $cognitoUser != null ? $cognitoUser['firstname'] : 'N/A',
                    'lastname' => $cognitoUser != null ? $cognitoUser['lastname'] : 'N/A',
                    'email' => $cognitoDbUser['email'],
                    'role' => $cognitoDbUser['role'],
                    'status' => $cognitoDbUser['status'],
                ];
            }
            $twig = \Pimcore::getContainer()->get('twig');
            $text = $twig->render('components/simple_table.html.twig', [
                'object' => $object,
                'headlines' => ['firstname', 'lastname', 'email', 'role', 'status'],
                'rows' => $rows,
            ]);
        }

        return $text;
    }
}
