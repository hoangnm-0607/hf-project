<?php

namespace App\Security\Validator;

use App\Exception\MissingPublicIdException;
use App\Exception\TokenIdMismatchException;
use App\Service\InMemoryUserReaderService;
use App\Traits\RequestStackTrait;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\InMemoryUser;

class InMemoryUserValidator
{
    use RequestStackTrait;

    private Security $security;
    private InMemoryUserReaderService $inMemoryUserReaderService;

    public function __construct(Security $security, InMemoryUserReaderService $inMemoryUserReaderService)
    {
        $this->security = $security;
        $this->inMemoryUserReaderService = $inMemoryUserReaderService;
    }

    /**
     * @throws TokenIdMismatchException
     */
    public function validateTokenAndApiUserId(string $apiUserId)
    {
        /** @var InMemoryUser $tokenUser */
        $tokenUser = $this->security->getUser();

        if (!$tokenUser) {
            throw new TokenIdMismatchException('Couldn\'t determine Token-User');
        }

        if ($tokenUser->getUserIdentifier() !== $apiUserId) {
            throw new TokenIdMismatchException('Token ID and API UserId don\'t match ('.$tokenUser->getUserIdentifier().' / '.$apiUserId.')');
        }
    }

    /**
     * @throws TokenIdMismatchException
     * @throws MissingPublicIdException
     */
    public function validateTokenAndAccessToRequestedEntityId(?string $entityId = null, string $requestAttributeName = 'publicId', ?string $role = null): string
    {
        if (null === $entityId) {
            $entityId = $this->requestStack->getCurrentRequest()->attributes->get($requestAttributeName);
            if (null === $entityId) {
                throw new MissingPublicIdException(sprintf('%s not found', $requestAttributeName));
            }
        }

        if ($decodedToken = $this->inMemoryUserReaderService->getUserIdentifier()) {
            foreach ($decodedToken as $row) {
                if ($this->checkRow($entityId, $requestAttributeName, $row) &&
                    $this->checkRow($role, 'role', $row, false)) {
                    return $entityId;
                }
            }
        }

        throw new TokenIdMismatchException(sprintf('Token ID and API %s don\'t match', $requestAttributeName));
    }

    private function checkRow(?string $value, string $key, array $row, bool $strict = true): bool
    {
        return (null === $value && !$strict) || (isset($row[$key]) && $row[$key] === $value);
    }
}
