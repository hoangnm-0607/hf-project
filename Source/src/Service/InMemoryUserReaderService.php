<?php

namespace App\Service;

use App\Exception\TokenIdMismatchException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;

class InMemoryUserReaderService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @throws TokenIdMismatchException
     */
    public function getUserIdentifier(): ?array
    {
        /** @var InMemoryUser $tokenUser */
        $tokenUser = $this->security->getUser();

        if (!$tokenUser instanceof UserInterface) {
            throw new TokenIdMismatchException('Couldn\'t determine Token-User');
        }

        $userIdentifier = $tokenUser->getUserIdentifier();

        return json_decode($userIdentifier, true);
    }

    public function getUser(): InMemoryUser
    {
        /** @var InMemoryUser $tokenUser */
        $tokenUser = $this->security->getUser();

        if (!$tokenUser instanceof UserInterface) {
            throw new TokenIdMismatchException('Couldn\'t determine Token-User');
        }

        return $tokenUser;
    }

    public function getExtraField(string $key): ?string
    {
        $user = $this->getUser();
        $fields = $user->getExtraFields();

        return $fields[$key] ?? null;
    }
}
