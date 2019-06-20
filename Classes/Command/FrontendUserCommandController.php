<?php
namespace UpAssist\Neos\FrontendLogin\Command;

/*                                                                             *
 * This script belongs to the TYPO3 Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Neos\Domain\Model\User;
use UpAssist\Neos\FrontendLogin\Domain\Service\FrontendUserService;

/**
 * @Flow\Scope("singleton")
 */
class FrontendUserCommandController extends CommandController
{

    /**
     * @Flow\Inject
     * @var FrontendUserService
     */
    protected $userService;

    /**
     * Create a new "Frontend user"
     *
     * @param string $username The username of the user to be created, used as an account identifier for the newly created account
     * @param string $password Password of the user to be created
     * @param string $givenName First name of the user to be created
     * @param string $familyName Last name of the user to be created
     * @return void
     */
    public function createCommand($username, $password, $givenName, $familyName)
    {
        $user = $this->userService->getUser($username);
        if ($user instanceof User) {
            $this->outputLine('The username "%s" is already in use', array($username));
            $this->quit(1);
        }
        $user = new User(['name' => $givenName . ' ' . $familyName]);
        $this->userService->addUser($user, $username, $password);
    }

}