<?php
namespace UpAssist\Neos\FrontendLogin\Controller;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin". *
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Error\Messages\Message;
use Neos\Flow\Mvc\ActionRequest;
use Neos\Flow\Security\Authentication\Controller\AbstractAuthenticationController;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Security\Exception\AuthenticationRequiredException;
use Neos\Fusion\View\FusionView;

/**
 * Controller for displaying login/logout forms and a profile for authenticated users
 */
class LoginController extends AbstractAuthenticationController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = FusionView::class;

    /**
     * @return void
     */
    public function loginAction()
    {
        $this->view->assign('account', $this->securityContext->getAccount());
    }

    /**
     *
     * @Flow\SkipCsrfProtection
     * @return void
     * @throws \Neos\Flow\Mvc\Exception\InvalidActionNameException
     * @throws \Neos\Flow\Mvc\Exception\InvalidArgumentNameException
     * @throws \Neos\Flow\Mvc\Exception\InvalidArgumentTypeException
     * @throws \Neos\Flow\Mvc\Exception\InvalidControllerNameException
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Flow\Mvc\Exception\UnsupportedRequestTypeException
     * @throws \Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException
     * @throws \Neos\Flow\Security\Exception\InvalidHashException
     */
    public function logoutAction()
    {

        if ($this->request->getInternalArgument('__suppressFlashMessage') !== true) {
            $this->addFlashMessage('Successfully logged out', 'Logged out', Message::SEVERITY_NOTICE);
        }

        $this->authenticationManager->logout();

        /** @var NodeInterface $logoutRedirectNode */
        $logoutRedirectNode = $this->request->getInternalArgument('__logoutRedirectNode');
        if ($logoutRedirectNode !== null) {
            $referer = $this->request->getReferringRequest();
            if ($referer->getControllerPackageKey() === 'Neos.Neos') {
                $this->redirect($referer->getControllerActionName(), $referer->getControllerName(), $referer->getControllerPackageKey(), $referer->getArguments());
            }
            $this->redirectToUri($logoutRedirectNode);
        }

        $this->redirect('index');
    }

    /**
     * @param ActionRequest $originalRequest The request that was intercepted by the security framework, NULL if there was none
     * @return string
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     * @throws \Neos\Flow\Mvc\Exception\UnsupportedRequestTypeException
     */
    protected function onAuthenticationSuccess(ActionRequest $originalRequest = NULL)
    {
        if ($this->request->getInternalArgument('__suppressFlashMessage') !== true) {
            $this->addFlashMessage('Successfully logged in', 'Logged in');
        }

        /** @var NodeInterface $redirectNode */
        $redirectNode = $this->request->getInternalArgument('__redirectNode');

        if ($redirectNode !== null) {
            $this->redirectToUri($redirectNode);
        }

        $this->redirect('status');
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
