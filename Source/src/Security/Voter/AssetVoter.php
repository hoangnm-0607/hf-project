<?php

declare(strict_types=1);

namespace App\Security\Voter;

use Pimcore\Model\Asset;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AssetVoter extends AbstractBaseVoter
{
    public const OWNER = 'file-owner';

    private const ATTRIBUTES = [
        self::OWNER,
    ];

    public function supportsAttribute(string $attribute): bool
    {
        return \in_array($attribute, self::ATTRIBUTES);
    }

    protected function supports($attribute, $subject): bool
    {
        return $subject instanceOf Asset && \in_array($attribute, self::ATTRIBUTES, true);
    }

    /**
     * @param string         $attribute
     * @param Asset          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUserId = $this->inMemoryUserReaderService->getExtraField('id');

        return match ($attribute) {
            self::OWNER =>  $subject->getMetadata('owner') === $currentUserId,
        };
    }
}
