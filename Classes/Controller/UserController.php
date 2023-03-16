<?php
namespace UpAssist\Neos\FrontendLogin\Controller;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Neos\Error\Messages\Notice;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Persistence\Exception\IllegalObjectTypeException;
use Neos\Flow\Session\Exception\SessionNotStartedException;
use Neos\Fusion\View\FusionView;
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
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

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
        $user = $user ?? $this->userService->getCurrentUser();
        $this->view->assign('namespace', $this->request->getArgumentNamespace());
        $this->view->assign('user', $user);
        $this->view->assign('electronicAddresses', $user->getElectronicAddresses());
        $this->view->assign('account', $user->getAccounts()->get(0));
        $this->view->assign('roleIdentifiers', $this->roleIdentifiers);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush());
    }

    /**
     * @param User $user
     * @return void
     */
    public function editAction(User $user): void
    {
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush());
        $this->view->assign('roleIdentifiers', $this->roleIdentifiers);
        $this->view->assign('user', $user);
    }

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
        $this->controllerContext->getFlashMessageContainer()->addMessage(new Notice('Successfully updated', null, [], 'Updated'));
        $this->redirect($this->request->getInternalArgument('__action') ?? 'show', null, null, ['user' => $user]);
    }

    /**
     * @return void
     */
    public function newAction()
    {
        $this->view->assign('roleIdentifiers', $this->roleIdentifiers);
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush());
    }

    /**
     * @param UserRegistrationDto $newUser
     * @return void
     * @throws StopActionException
     */
    public function createAction(UserRegistrationDto $newUser)
    {
        $this->userService->addUser($newUser->getUsername(), $newUser->getPassword(), $newUser->getUser(), [$newUser->getRoleIdentifier()]);
        $this->redirect('index');
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('flashMessages', $this->controllerContext->getFlashMessageContainer()->getMessagesAndFlush());
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
        $this->persistenceManager->persistAll();
        $this->redirect($this->request->getInternalArgument('__action') ?? 'index');
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
