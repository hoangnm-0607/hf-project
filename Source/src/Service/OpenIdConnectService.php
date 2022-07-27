<?php

namespace App\Service;


use App\DataMapper\OpenIdConnectUserMapperInterface;
use Exception;
use Jumbojett\OpenIDConnectClient;
use Jumbojett\OpenIDConnectClientException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Pimcore\Model\User;

class OpenIdConnectService
{

    private OpenIDConnectClient $openIDConnectClient;

    private OpenIdConnectUserMapperInterface $mapper;

    private array $defaultRoles;

    private string $userFolderName;


    public function __construct(OpenIdConnectUserMapperInterface $mapper, string $providerUrl, string $clientId,
                                string $clientSecret, array $scopes, array $defaultRoles, string $userFolderName) {
        $this->openIDConnectClient = new OpenIDConnectClient($providerUrl, $clientId, $clientSecret);
        $this->openIDConnectClient->addScope($scopes);

        $this->defaultRoles = $defaultRoles;

        $this->userFolderName = $userFolderName;

        $this->mapper = $mapper;
    }


    /**
     * @throws Exception
     */
    protected function getUserRoleNames($username): array
    {
        $roles = array();

        //Get user
        $user = $this->getPimcoreUserByUsername($username);
        if ($user instanceof User) {
            //If the user is an admin add the role ROLE_PIMCORE_ADMIN automatically
            if ($user->isAdmin()) {
                $roles[] = 'ROLE_PIMCORE_ADMIN';
            }

            //Get user's roles
            foreach ($user->getRoles() as $roleId) {
                $role = $this->getPimcoreUserRoleById($roleId);
                $roles[] = $role->getName();
            }
        }

        return $roles;
    }

    /**
     * @throws OpenIDConnectClientException
     */
    public function authenticate(string $username, string $password)
    {
        //Check if credentials are valid
        if (empty($password)) {
            throw new BadCredentialsException('The presented password is not valid.');
        }

        $this->openIDConnectClient->addAuthParam([
            'username' => $username,
            'password' => $password
        ]);

        $token = $this->openIDConnectClient->requestResourceOwnerToken(TRUE);

        if (!$token) {
            throw new BadCredentialsException('The presented username is not valid.');
        }

        $this->openIDConnectClient->setAccessToken($token->access_token);
    }

    /**
     * @throws Exception
     */
    protected function getPimcoreUserByUsername(string $username): ?User\AbstractUser
    {
        return User::getByName($username);
    }


    /**
     * @throws Exception
     */
    protected function getPimcoreUserFolder(): User\AbstractUser
    {
        return User\Folder::getByName($this->userFolderName);
    }

    protected function getPimcoreUserRoleById(int $id): User\AbstractUser
    {
        return User\Role::getById($id);
    }

    /**
     * @throws Exception
     */
    protected function getPimcoreUserRoleByName(string $name): User\AbstractUser
    {
        return User\Role::getByName($name);
    }


    public function updatePimcoreUser(string $username, string $password): User|User\AbstractUser
    {
        try {
            //Get Pimcore user
            $user = $this->getPimcoreUserByUsername($username);

            //If Pimcore user doesn't exists create a new one
            if (!($user instanceof User)) {
                $user = new User();
                $user->setParentId($this->getPimcoreUserFolder()->getId());

                //Add default roles
                $user->setRoles(array_unique(
                    array_merge(
                        $user->getRoles(),
                        $this->getDefaultRolesIds()
                    )
                ));
            }

            //Update user's data
            $this->mapper::mapDataToUser($user, $username, $password, $this->openIDConnectClient);


            $user->save();

            return $user;
        } catch (Exception $exception) {
            echo $exception->getMessage();
            throw new BadCredentialsException(sprintf('Unable to update Pimcore user %s', $username));
        }
    }

    /**
     * @throws Exception
     */
    protected function getDefaultRolesIds(): array
    {
        $defaultRolesIds = array();

        foreach ($this->defaultRoles as $default_role) {
            $pimcoreRole = $this->getPimcoreUserRoleByName($default_role);
            if ($pimcoreRole) {
                $defaultRolesIds[] = $pimcoreRole->getId();
            }
        }

        return $defaultRolesIds;
    }
}
