<?php
namespace UpAssist\Neos\FrontendLogin\Controller;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;
use Neos\Flow\Session\Exception\SessionNotStartedException;
use Neos\Neos\Domain\Model\User;
use Neos\Neos\Domain\Repository\UserRepository;
use UpAssist\Neos\FrontendLogin\Domain\Service\FrontendUserService;
use UpAssist\Neos\FrontendLogin\Domain\Model\Dto\UserRegistrationDto;

/**
 * Controller for displaying a simple user profile for frontend users
 */
class UserController extends ActionController
{

    /**
     * @Flow\Inject
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject
     * @var FrontendUserService
     */
    protected $userService;

    /**
     * @Flow\InjectConfiguration(path="roleIdentifiers")
     * @var array
     */
    protected $roleIdentifiers;

    /**
     * @return void
     */
    public function showAction(User $user = null)
    {
        $this->view->assign('namespace', $this->request->getArgumentNamespace());
        $this->view->assign('user', $user ?? $this->userService->getCurrentUser());
        $this->view->assign('account', $user ? $user->getAccounts()->get(0) : $this->userService->getCurrentAccount());
        $this->view->assign('roleIdentifiers', $this->roleIdentifiers);
    }

//    public function initializeUpdateAction()
//    {
//        \Neos\Flow\var_dump($this->request->getArguments());die();
//    }

    /**
     * @param User $user
     * @param string $password
     * @return void
     * @throws StopActionException
     */
    public function updateAction(User $user, string $password = null, string $roleIdentifiers = null)
    {
        if ($password) {
            try {
                $this->userService->setUserPassword($user, $password);
            } catch (IllegalObjectTypeException $e) {
            } catch (SessionNotStartedException $e) {
            }
        }

        if ($roleIdentifiers) {
            $this->userService->setRolesForAccount($user->getAccounts()->get(0), [$roleIdentifiers]);
        }

        $this->userService->updateUser($user);

        $this->redirect('show', null, null, ['user' => $user]);
    }

    /**
     * @return void
     */
    public function newAction()
    {
        $this->view->assign('roleIdentifiers', $this->roleIdentifiers);
    }

    /**
     * @param UserRegistrationDto $newUser
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function createAction(UserRegistrationDto $newUser)
    {
        $this->userService->addUser($newUser->getUsername(), $newUser->getPassword(), $newUser->getUser(), [$newUser->getRoleIdentifier()]);

        $this->redirect('index', 'login');
    }

    /**
     * @return void
     */
    public function indexAction()
    {
       $this->view->assign('users', $this->userService->findAll());
    }

    /**
     * @param User $user
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Neos\Domain\Exception
     */
    public function deleteAction(User $user)
    {
        $this->userService->deleteUser($user);
        $this->redirect('index');
    }

    /**
     * Disable the technical error flash message
     *
     * @return boolean
     */
    protected function getErrorFlashMessage()
    {
        return FALSE;
    }
}
