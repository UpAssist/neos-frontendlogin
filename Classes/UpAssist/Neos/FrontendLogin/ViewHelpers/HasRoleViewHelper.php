<?php
namespace UpAssist\Neos\FrontendLogin\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;
use TYPO3\Flow\Security\Policy\PolicyService;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Neos\Domain\Model\User;

/**
 * Class HasRoleViewHelper
 * @package UpAssist\Neos\FrontendLogin\ViewHelpers
 */
class HasRoleViewHelper extends AbstractViewHelper
{

    /**
     * @Flow\Inject
     * @var PolicyService
     */
    protected $policyService;

    /**
     * @param User|null $user
     * @param $role
     * @return bool
     * @throws \TYPO3\Flow\Security\Exception\NoSuchRoleException
     */
    public function render(User $user = null, $role)
    {
        if ($user === null) {
            $user = $this->renderChildren();
        }

        /** @var Account $account */
        foreach ($user->getAccounts() as $account) {
            if ($account->hasRole($this->policyService->getRole($role))) return true;
        }

        return false;
    }
}
