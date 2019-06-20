<?php

namespace UpAssist\Neos\FrontendLogin\Domain\Service;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Neos\Domain\Service\UserService;

/**
 * Central authority to deal with "frontend users"
 *
 */
class FrontendUserService extends UserService
{
    protected $defaultAuthenticationProviderName = 'UpAssist.Neos.FrontendLogin:Frontend';

    /**
     * Returns the currently logged in user, if any
     *
     * @return Account The currently logged in user, or null
     * @api
     */
    public function getCurrentAccount()
    {
        if ($this->securityContext->canBeInitialized() === true) {
            $account = $this->securityContext->getAccount();
            if ($account !== null) {
                return $account;
            }
        }

        return null;
    }

    /**
     * @param string $value
     * @return string
     * @throws \Neos\Neos\Domain\Exception
     */
    public function getAccountIdentifierByLastName($value)
    {
        $query = $this->accountRepository->createQuery();
        $accounts = $query->matching(
            $query->logicalAnd(
                $query->equals('authenticationProviderName', $this->defaultAuthenticationProviderName)
            )
        )->execute()->toArray();

        /** @var Account $account */
        foreach ($accounts as $account) {
            if ($this->getUser($account->getAccountIdentifier())->getName()->getLastName() === $value) {
                return $account->getAccountIdentifier();
            }
        }

        return null;
    }
}
