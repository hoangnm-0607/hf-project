<?php

namespace App\Security\Guard;

use App\Security\Validator\CognitoTokenValidator;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private CognitoTokenValidator $tokenValidator;

    public function __construct(CognitoTokenValidator $tokenValidator)
    {
        $this->tokenValidator = $tokenValidator;
    }

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        return new JsonResponse(['message' => 'Authentication Required'], Response::HTTP_UNAUTHORIZED);

    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization') && str_starts_with($request->headers->get('Authorization'), 'Bearer');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        // skip beyond "Bearer "
        return substr($authorizationHeader, 7);
    }

    /**
     * @throws Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?InMemoryUser
    {
        /** got the token here in $credentials, so we're
         * - validate the token
         * - extract the token payload
         * - return a new InMemoryUser with gehashte_user_id as Identifier
         */
        if ($tokenValue = $this->tokenValidator->validateToken($credentials)) {
            $username =
                $tokenValue['custom:gehashte_user_id'] ??
                $tokenValue['partner_role_status'] ??
                $tokenValue['company_role_status'] ??
                null
            ;

            $email = $tokenValue['email'] ?? null;
            $sub = $tokenValue['sub'] ?? null;

            $password = $tokenValue['custom:activation_key'] ?? null;

            if (null !== $username) {
                return new InMemoryUser(
                    $username,
                    $password,
                    [],
                    true,
                    true,
                    true,
                    true,
                    [
                        'email' => $email,
                        'id' => $sub,
                    ]
                );
            }
        }

        return null;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
