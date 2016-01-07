<?php
namespace UpAssist\Neos\FrontendLogin\Controller\Module\Administration;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Neos\Domain\Model\User;


/**
 * Class UsersController
 * @package UpAssist\Neos\FrontendLogin\Controller\Module\Administration
 */
class UsersController extends \TYPO3\Neos\Controller\Module\Administration\UsersController
{
    /**
     * @param User $user
     */
    public function newAction(User $user = null)
    {
        $this->view->assign('authenticationProviders', ['UpAssist.Neos.FrontendLogin:Frontend' => 'Website User', 'Typo3BackendProvider' => 'Neos User']);
        parent::newAction($user);
    }

    /**
     * Create a new user
     *
     * @param string $username The user name (ie. account identifier) of the new user
     * @param array $password Expects an array in the format array('<password>', '<password confirmation>')
     * @param User $user The user to create
     * @param array $roleIdentifiers A list of roles (role identifiers) to assign to the new user
     * @param string $authenticationProvider
     * @Flow\Validate(argumentName="username", type="\TYPO3\Flow\Validation\Validator\NotEmptyValidator")
     * @Flow\Validate(argumentName="username", type="\TYPO3\Neos\Validation\Validator\UserDoesNotExistValidator")
     * @Flow\Validate(argumentName="password", type="\TYPO3\Neos\Validation\Validator\PasswordValidator", options={ "allowEmpty"=0, "minimum"=1, "maximum"=255 })
     * @return void
     */
    public function createAction($username, array $password, User $user, array $roleIdentifiers, $authenticationProvider = null)
    {
        $this->userService->addUser($username, $password[0], $user, $roleIdentifiers, $authenticationProvider);
        $this->addFlashMessage('The user "%s" has been created.', 'User created', Message::SEVERITY_OK, [htmlspecialchars($username)], 1416225561);
        $this->redirect('index');
    }
}
