<?php


namespace App\EventListener;

use App\Service\OpenIdConnectService;
use Exception;
use Pimcore\Event\Admin\Login\LoginCredentialsEvent;
use Pimcore\Event\Admin\Login\LoginFailedEvent;

class LoginListener
{
    private OpenIdConnectService $openIdConnectService;


    public function __construct(OpenIdConnectService $openIdConnectService)
    {
        $this->openIdConnectService = $openIdConnectService;
    }

    /**
     * @param LoginCredentialsEvent $event
     */
    public function onAdminLoginCredentials(LoginCredentialsEvent $event)
    {
        //Get credentials from the login event
        $credentials = $event->getCredentials();

        //If authentication via token skip the LDAP authentication
        if (isset($credentials['token'])) {
            return;
        }

        $username = $credentials['username'];
        $password = $credentials['password'];

        if (!str_ends_with($username,'@hansefit.de') ) {
            return;
        }

        try {
            //Authenticate via openId
            $this->openIdConnectService->authenticate($username, $password);

            //Update Pimcore user
            $this->openIdConnectService->updatePimcoreUser($username, $password);
        } catch (Exception) {
            return;
        }
    }

    /**
     * @param LoginFailedEvent $event
     */
    public function onAdminLoginFailed(LoginFailedEvent $event)
    {
        //Get credentials from the login event
        $username = $event->getCredential('username');
        $password = $event->getCredential('password');

        if (!str_ends_with($username,'@hansefit.de') ) {
            return;
        }

        try {
            //authenticate via openId
            $this->openIdConnectService->authenticate($username, $password);

            //Update Pimcore user
            $pimcoreUser = $this->openIdConnectService->updatePimcoreUser($username, $password);

            //Update session
            $event->setUser($pimcoreUser);
        } catch (Exception) {
            return;
        }
    }
}
