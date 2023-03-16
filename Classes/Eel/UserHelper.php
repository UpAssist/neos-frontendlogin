<?php
namespace UpAssist\Neos\FrontendLogin\Eel;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Configuration\Exception\InvalidConfigurationTypeException;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Exception\NoSuchRoleException;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\Flow\Security\Policy\Role;
use Neos\Neos\Domain\Exception;
use Neos\Neos\Domain\Model\User;
use UpAssist\Neos\FrontendLogin\Domain\Service\FrontendUserService;
use  Neos\Flow\Annotations as Flow;
class UserHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var FrontendUserService
     */
    protected FrontendUserService $userService;

    /**
     * @var array
     * @Flow\InjectConfiguration (path="roleIdentifiers")
     */
    protected $configuredRoles = [];

    /**
     * @var PolicyService $policyService
     * @Flow\Inject
     */
    protected PolicyService $policyService;

    /**
     * @param Account|null $account
     * @return User|null
     * @throws Exception
     */
    public function getUserByAccount(Account $account = null): ?\Neos\Neos\Domain\Model\User
    {
        $accountIdentifier = $account ? $account->getAccountIdentifier() : $this->userService->getCurrentAccount()->getAccountIdentifier();
        return $this->userService->getUser($accountIdentifier);

    }

    /**
     * @param Account|null $account
     * @return array
     */
    public function getUserRolesByAccount(Account $account = null): array
    {
        $account = $account ?? $this->userService->getCurrentAccount();
        $roles = [];
        /** @var Role $role */
        foreach ($account->getRoles() as $role) {
            $filteredRole = array_filter($this->configuredRoles, function ($configuredRole) use ($role) {
                return $configuredRole['key'] === $role->getIdentifier();
            });
            if (!empty($filteredRole)) {
                $roles[] = array_values($filteredRole)[0];
            }

        }


        return $roles;
    }

    /**
     * @param User $user
     * @param string $roleIdentifier
     * @return bool
     * @throws InvalidConfigurationTypeException
     * @throws \Neos\Flow\Security\Exception
     * @throws NoSuchRoleException
     */
    public function hasRole(User $user, string $roleIdentifier): bool
    {
        /** @var Account $account */
        foreach ($user->getAccounts() as $account) {
            if ($account->hasRole($this->policyService->getRole($roleIdentifier))) return true;
        }

        return false;
    }

    /**
     * @param $methodName
     * @return bool
     */
    public function allowsCallOfMethod($methodName): bool
    {
        return true;
    }
}
