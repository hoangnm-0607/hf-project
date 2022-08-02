<?php

namespace App\Service\TextService;

use App\Entity\Company;
use App\Service\CognitoDynamoDbManagementService;
use Aws\DynamoDb\Exception\DynamoDbException;
use Pimcore\Model\DataObject\ClassDefinition\Layout\DynamicTextLabelInterface;

class CompanyUsersService implements DynamicTextLabelInterface
{
    /**
     * @param Company $object
     */
    public function renderLayoutText($data, $object, $params): string
    {
        $text = '';

        if (null !== $object) {
            // CU Admin invites
            try {
                $cognitoDynamoDbService = new CognitoDynamoDbManagementService();
                $result = $cognitoDynamoDbService->getAllRowsById(
                    $object->getId(),
                    CognitoDynamoDbManagementService::CCP_ADMIN_KEY,
                    CognitoDynamoDbManagementService::CCP_ADMIN_TABLE_NAME,
                );
            } catch (DynamoDbException $e) {
                return $e->getMessage();
            }

            $rows = [];
            foreach ($result as $cognitoDbUser) {
                if ($cognitoDbUser['status'] != 'Pending' || $cognitoDbUser['expireTimestamp'] < time()) {
                    continue;
                }
                $rows[] = [
                    'firstname' => $cognitoDbUser['name'],
                    'lastname' => $cognitoDbUser['surname'],
                    'email' => $cognitoDbUser['email'],
                    'position' => $cognitoDbUser['position'],
                    'status' => $cognitoDbUser['status'],
                    'validTill' => date('Y-m-d', $cognitoDbUser['expireTimestamp'])
                ];
            }

            $twig = \Pimcore::getContainer()->get('twig');
            $text = $twig->render('components/simple_table.html.twig', [
                'object' => $object,
                'headlines' => ['firstname', 'lastname', 'email', 'position', 'status', 'valid_till'],
                'rows' => $rows,
                'title' => 'admin.label.pending_invite',
            ]);

            // CU

            try {
                $cognitoDynamoDbService = new CognitoDynamoDbManagementService();
                $result = $cognitoDynamoDbService->getAllRowsById(
                    $object->getId(),
                    CognitoDynamoDbManagementService::CCP_KEY,
                    CognitoDynamoDbManagementService::CCP_TABLE_NAME,
                    CognitoDynamoDbManagementService::CCP_INDEX_NAME,
                );
            } catch (DynamoDbException $e) {
                return $e->getMessage();
            }


            $rows = [];
            foreach ($result as $cognitoDbUser) {
                $rows[] = [
                    'firstname' => $cognitoDbUser['name'],
                    'lastname' => $cognitoDbUser['surname'],
                    'email' => $cognitoDbUser['email'],
                    'position' => $cognitoDbUser['position'],
                    'role' => $cognitoDbUser['role'],
                    'status' => $cognitoDbUser['status'],
                ];
            }

            $text .= $twig->render('components/simple_table.html.twig', [
                'object' => $object,
                'headlines' => ['firstname', 'lastname', 'email', 'position', 'role', 'status'],
                'rows' => $rows,
                'title' => 'admin.label.company_user',
            ]);
        }


        return $text;
    }
}
