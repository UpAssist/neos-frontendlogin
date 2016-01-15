<?php
namespace UpAssist\Neos\FrontendLogin\Controller;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin". *
 *                                                                             */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\ActionRequest;
use TYPO3\Flow\Security\Authentication\Controller\AbstractAuthenticationController;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * Controller for displaying login/logout forms and a profile for authenticated users
 */
class LoginController extends AbstractAuthenticationController
{

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('account', $this->securityContext->getAccount());
    }

    /**
     * return void
     */
    public function logoutAction()
    {
        parent::logoutAction();

        if ($this->request->getInternalArgument('__suppressFlashMessage') !== true) {
            $this->addFlashMessage('Successfully logged out', 'Logged out', Message::SEVERITY_NOTICE);
        }

        /** @var NodeInterface $logoutRedirectNode */
        $logoutRedirectNode = $this->request->getInternalArgument('__logoutRedirectNode');
        if ($logoutRedirectNode !== null) {
            $referer = $this->request->getReferringRequest();
            if ($referer->getControllerPackageKey() === 'TYPO3.Neos') {
                $this->redirect($referer->getControllerActionName(), $referer->getControllerName(), $referer->getControllerPackageKey(), $referer->getArguments());
            }
            $this->redirectToUri($logoutRedirectNode);
        }

        $this->redirect('index');
    }

    /**
     * @param ActionRequest $originalRequest The request that was intercepted by the security framework, NULL if there was none
     * @return string
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