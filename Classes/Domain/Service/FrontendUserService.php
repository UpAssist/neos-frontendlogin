<?php

namespace UpAssist\Neos\FrontendLogin\Domain\Service;

/*                                                                             *
 * This script belongs to the Neos Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Neos\Domain\Exception;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Service\UserService;
use Neos\Party\Domain\Model\ElectronicAddress;

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
     * @param User $user
     * @return Account|null
     */
    public function getAccountByUser(User $user): ?Account
    {
        return isset($user->getAccounts()->toArray()[0]) ? $user->getAccounts()->toArray()[0] : null;
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

    /**
     * @param string $emailAddress
     * @return Account|null
     * @throws Exception
     */
    public function getAccountByEmailAddress(string $emailAddress): ?Account
    {
        $query = $this->accountRepository->createQuery();
        $accounts = $query->matching(
            $query->logicalAnd(
                $query->equals('authenticationProviderName', $this->defaultAuthenticationProviderName)
            )
        )->execute()->toArray();

        /** @var Account $account */
        foreach ($accounts as $account) {
            $user = $this->getUser($account->getAccountIdentifier());

            if (isset($user->getElectronicAddresses()[0]) && $user->getElectronicAddresses()[0]->getIdentifier() === $emailAddress) {
                return $account;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $query = $this->accountRepository->createQuery();
        $accounts = $query->matching(
            $query->logicalAnd(
                $query->equals('authenticationProviderName', $this->defaultAuthenticationProviderName)
            )
        )->execute()->toArray();
        $users = [];

        /** @var Account $account */
        foreach ($accounts as $account) {
            $users[] = $this->partyService->getAssignedPartyOfAccount($account);
        }

        return $users;
    }

    /**
     * @param string $username
     * @param string $password
     * @param User $user
     * @param array|null $roleIdentifiers
     * @param null $authenticationProviderName
     * @return User
     */
    public function addUser($username, $password, User $user, array $roleIdentifiers = null, $authenticationProviderName = null)
    {
        return parent::addUser($username, $password, $user, $roleIdentifiers, $authenticationProviderName);
        // TODO: Prevent duplicates (now nasty sql error)
    }

}
