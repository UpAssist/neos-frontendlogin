<?php
namespace UpAssist\Neos\FrontendLogin\ViewHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Account;
use Neos\FluidAdaptor\Core\ViewHelper\AbstractViewHelper;
use Neos\Neos\Domain\Model\User;
use UpAssist\Neos\FrontendLogin\Domain\Service\FrontendUserService;

/**
 * Class UserByAccountViewHelper
 * @package UpAssist\Neos\FrontendLogin\ViewHelpers
 */
class UserByAccountViewHelper extends AbstractViewHelper
{

    
	/**
	 * NOTE: This property has been introduced via code migration to ensure backwards-compatibility.
	 * @see AbstractViewHelper::isOutputEscapingEnabled()
	 * @var boolean
	 */
	protected $escapeOutput = FALSE;

    /**
     * @Flow\Inject
     * @var FrontendUserService
     */
    protected $userService;

    /**
     * @param Account $account
     * @return User
     * @throws \Neos\Neos\Domain\Exception
     */
    public function render(Account $account = null)
    {
        if ($account === null) {
            $account = $this->renderChildren();
        }

        return $this->userService->getUser($account->getAccountIdentifier());

    }
}
