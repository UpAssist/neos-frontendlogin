<?php
namespace UpAssist\Neos\FrontendLogin\Domain\Repository;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;
use TYPO3\Neos\Domain\Model\User;

/**
 * @Flow\Scope("singleton")
 */
class UserRepository extends \TYPO3\Neos\Domain\Repository\UserRepository
{

    /**
     * @param Account $account
     * @return User
     */
    public function findOneHavingAccount(Account $account)
    {
        $query = $this->createQuery();
        return
            $query->matching(
                $query->contains('accounts', $account)
            )
                ->execute()
                ->getFirst();
    }

}