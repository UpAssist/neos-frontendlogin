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
     *
     * @Flow\SkipCsrfProtection
     * @return void
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