<?php
namespace UpAssist\Neos\FrontendLogin\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Policy\PolicyService;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\Neos\Domain\Model\User;

/**
 * Class HasRoleViewHelper
 * @package UpAssist\Neos\FrontendLogin\ViewHelpers
 */
class HasRoleViewHelper extends AbstractViewHelper
{

    
	/**
	 * NOTE: This property has been introduced via code migration to ensure backwards-compatibility.
	 * @see AbstractViewHelper::isOutputEscapingEnabled()
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

    /**
     * @Flow\Inject
     * @var PolicyService
     */
    protected $policyService;

    /**
     * @param User|null $user
     * @param $role
     * @return bool
     * @throws \Neos\Flow\Security\Exception\NoSuchRoleException
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
