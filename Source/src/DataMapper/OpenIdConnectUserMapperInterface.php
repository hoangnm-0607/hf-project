<?php


namespace App\DataMapper;

use Jumbojett\OpenIDConnectClient;
use Pimcore\Model\User;

interface OpenIdConnectUserMapperInterface
{

    public static function mapDataToUser(User $user, string $username, string $password, OpenIDConnectClient $openIDConnectClient);
}
