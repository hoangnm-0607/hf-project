<?php

declare(strict_types=1);

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CompanyVoter extends AbstractBaseVoter
{
    public const ADMIN = 'company-admin';
    public const MANAGER = 'company-manager';
    public const ANY_ROLE = 'company-any-role';

    private const ATTRIBUTES = [
        self::ADMIN,
        self::ANY_ROLE,
    ];

    public function supportsAttribute(string $attribute): bool
    {
        return \in_array($attribute, self::ATTRIBUTES);
    }

    protected function supports($attribute, $subject): bool
    {
        return \is_string($subject) && \in_array($attribute, self::ATTRIBUTES, true);
    }

    /**
     * @param string         $attribute
     * @param string         $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $decodedToken = $this->inMemoryUserReaderService->getUserIdentifier();

        foreach ($decodedToken as $row) {
            $result = match ($attribute) {
                self::ADMIN => ($this->checkRow($subject, 'companyId', $row) && $this->checkRow('Admin', 'role', $row, false)),
                self::MANAGER => ($this->checkRow($subject, 'companyId', $row) && $this->checkRow('Usermgmt', 'role', $row, false)),
                self::ANY_ROLE => $this->checkRow($subject, 'companyId', $row),
            };

            if ($result) {
                return true;
            }
        }

        return false;
    }
}
