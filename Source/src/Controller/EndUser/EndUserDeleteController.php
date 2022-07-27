<?php

namespace App\Controller\EndUser;

use App\Service\EndUser\EndUserManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class EndUserDeleteController
{
    private EndUserManager $endUserManager;

    public function __construct(EndUserManager $endUserManager)
    {
        $this->endUserManager = $endUserManager;
    }

    public function __invoke(int $endUserId): ?JsonResponse
    {
        $user = $this->endUserManager->findOneOrThrowException($endUserId);
        $this->endUserManager->removeUser($user);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
