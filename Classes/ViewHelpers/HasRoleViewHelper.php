<?php
namespace UpAssist\Neos\FrontendLogin\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\Flow\Security\Exception\NoSuchRoleException;
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
     * @throws \Neos\FluidAdaptor\Core\ViewHelper\Exception
     */
    public function initializeArguments()
    {
        $this->registerArgument('user', 'Neos\Neos\Domain\Model\User', 'The user');
        $this->registerArgument('role', 'string', 'The user role', TRUE);
        parent::initializeArguments();
    }

    /**
     * @return boolean
     * @throws NoSuchRoleException
     */
    public function render()
    {
        if ($this->arguments['user'] === null) {
            $this->arguments['user'] = $this->renderChildren();
        }

        $user = $this->arguments['user'];
        /** @var Account $account */
        foreach ($user->getAccounts() as $account) {
            if ($account->hasRole($this->policyService->getRole($this->arguments['role']))) return true;
        }

        return false;
    }

}
