<?php


namespace App\DataMapper;

use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;
use Pimcore\Model\User;
use Pimcore\Tool\Authentication;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class OpenIdConnectUserMapper implements OpenIdConnectUserMapperInterface
{


    /**
     * @throws OpenIDConnectClientException
     */
    public static function mapDataToUser(User $user, string $username, string $password, OpenIDConnectClient $openIDConnectClient)
    {
        $user->setUsername($username);

        $user->setPassword(self::encodePassword($username, $password));

        $user->setFirstname($openIDConnectClient->requestUserInfo('given_name'));
        $user->setLastname($openIDConnectClient->requestUserInfo('family_name'));
        $user->setEmail($openIDConnectClient->requestUserInfo('email'));

        $user->setActive(true);
    }

    private static function encodePassword(string $username, string $password): string
    {
        try {
            return Authentication::getPasswordHash($username, $password);
        } catch (\Exception) {
            throw new BadCredentialsException(sprintf('Unable to create password hash for user: %s', $username));
        }
    }
}
