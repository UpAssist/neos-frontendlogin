<?php
namespace UpAssist\Neos\FrontendLogin\Domain\Repository;

/*
 * This file is part of the UpAssist.Neos.FrontendLogin package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class PasswordResetTokenRepository extends Repository
{
    /**
     * @return void
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function removeExpiredTokens(): void
    {
        $expirationThreshold = new \DateTimeImmutable('-1 hour');

        $query = $this->createQuery();
        $query->matching(
            $query->lessThan('createdAt', $expirationThreshold)
        );

        $expiredTokens = $query->execute();

        foreach ($expiredTokens as $token) {
            $this->remove($token);
        }
    }
}
