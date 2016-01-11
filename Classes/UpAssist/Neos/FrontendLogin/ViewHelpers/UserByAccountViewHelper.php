<?php
namespace UpAssist\Neos\FrontendLogin\ViewHelpers;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;
use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Neos\Domain\Model\User;
use UpAssist\Neos\FrontendLogin\Domain\Service\FrontendUserService;

/**
 * Class UserByAccountViewHelper
 * @package UpAssist\Neos\FrontendLogin\ViewHelpers
 */
class UserByAccountViewHelper extends AbstractViewHelper
{

    /**
     * @Flow\Inject
     * @var FrontendUserService
     */
    protected $userService;

    /**
     * @param Account $account
     * @return User
     * @throws \TYPO3\Neos\Domain\Exception
     */
    public function render(Account $account = null)
    {
        if ($account === null) {
            $account = $this->renderChildren();
        }

        return $this->userService->getUser($account->getAccountIdentifier());

    }
}
