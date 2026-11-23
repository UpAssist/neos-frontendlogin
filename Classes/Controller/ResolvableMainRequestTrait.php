<?php

namespace UpAssist\Neos\FrontendLogin\Controller;

/*                                                                             *
 * This script belongs to the Neos Flow package "UpAssist.Neos.FrontendLogin". *
 *                                                                             */

use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Flow\Mvc\ActionRequest;

/**
 * Lets the plugin build its own URIs when it is rendered outside a routed
 * frontend request, e.g. on the 404 page rendered by Neos' FusionExceptionView.
 *
 * That view creates a main request with only "@package: Neos.Neos" and no node,
 * so no route resolves for the form targets and links of the plugin. In Neos 8.4
 * the absorbed exception then re-renders the form while its backtrace is logged,
 * which never ends (neos/flow-development-collection#3487).
 */
trait ResolvableMainRequestTrait
{
    protected function makeMainRequestResolvable(ActionRequest $request): void
    {
        $mainRequest = $request->getMainRequest();
        if ($mainRequest->hasArgument('node')) {
            return;
        }

        $documentNode = $request->getInternalArgument('__documentNode');
        if (!$documentNode instanceof NodeInterface) {
            return;
        }

        $mainRequest->setControllerPackageKey('Neos.Neos');
        $mainRequest->setControllerName('Frontend\Node');
        $mainRequest->setControllerActionName('show');
        $mainRequest->setArgument('node', $documentNode->getContextPath());
    }
}
