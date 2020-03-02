<?php
namespace UpAssist\Neos\FrontendLogin\Controller;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
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
     * @return void
     */
    public function showAction()
    {
        $this->view->assign('user', $this->userService->getCurrentUser());
        $this->view->assign('account', $this->userService->getCurrentAccount());
    }

    /**
     * @param User $user
     * @return void
     */
    public function updateAction(User $user)
    {
        $this->userService->updateUser($user);
        $this->addFlashMessage('Successfully updated user data', 'Success');

        $this->redirect('show');
    }

    /**
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * @param UserRegistrationDto $newUser
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function createAction(UserRegistrationDto $newUser)
    {
        $this->userService->addUser($newUser->getUsername(), $newUser->getPassword(), $newUser->getUser(), ['UpAssist.Neos.FrontendLogin:User']);
        $this->addFlashMessage('Your account has been created, you can login now.');

        $this->redirect('index', 'login');
    }

    /**
     *
     */
    public function indexAction()
    {
       $this->view->assign('users', $this->userRepository->findAll());
    }

    /**
     * @param User $user
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Neos\Domain\Exception
     */
    public function deleteAction(User $user)
    {
        $this->userService->deleteUser($user);
        $this->addFlashMessage('Removed user');
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
