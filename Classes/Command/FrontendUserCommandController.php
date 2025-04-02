<?php
namespace UpAssist\Neos\FrontendLogin\Command;

/*                                                                             *
 * This script belongs to the Neos Flow package "UpAssist.Neos.FrontendLogin".*
 *                                                                             */

use Doctrine\Common\Collections\ArrayCollection;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Cli\Exception\StopCommandException;
use Neos\Neos\Domain\Exception;
use Neos\Neos\Domain\Model\User;
use Neos\Party\Domain\Model\ElectronicAddress;
use Neos\Party\Domain\Model\PersonName;
use UpAssist\Neos\FrontendLogin\Domain\Model\Dto\UserRegistrationDto;
use UpAssist\Neos\FrontendLogin\Domain\Repository\PasswordResetTokenRepository;
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
    protected FrontendUserService $userService;

    /**
     * @Flow\Inject
     * @var PasswordResetTokenRepository
     */
    protected PasswordResetTokenRepository $passwordResetTokenRepository;

    /**
     * Create a new "Frontend user"
     *
     * @param string $username The username of the user to be created, used as an account identifier for the newly created account
     * @param string $password Password of the user to be created
     * @param string $givenName First name of the user to be created
     * @param string $familyName Last name of the user to be created
     * @param string $email
     * @param string $role
     * @return void
     * @throws Exception
     * @throws StopCommandException
     */
    public function createCommand(string $username, string $password, string $givenName, string $familyName, string $email, string $role = 'UpAssist.Neos.FrontendLogin:User'): void
    {
        $user = $this->userService->getUser($username);
        if ($user instanceof User) {
            $this->outputLine('The username "%s" is already in use', array($username));
            $this->quit(1);
        }
        $neosUser = new User();
        $name = new PersonName();
        $name->setFirstName($givenName);
        $name->setLastName($familyName);
        $neosUser->setName($name);
        $newUser = new UserRegistrationDto();
        $newUser->setUser($neosUser);
        $newUser->setUsername($username);
        $newUser->setPassword($password);
        $emailAddress = new ElectronicAddress();
        $emailAddress->setIdentifier($email);
        $emailAddress->setType('Email');
        $collection = new ArrayCollection([$emailAddress]);
        $newUser->setElectronicAddresses($collection);
        $this->userService->addUser($newUser->getUsername(), $newUser->getPassword(), $newUser->getUser(), [$newUser->getRoleIdentifier()]);

    }

    /**
     * Cleanup old password reset tokens
     * @return void
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     * @throws \Neos\Flow\Persistence\Exception\InvalidQueryException
     */
    public function cleanExpiredTokensCommand(): void
    {
        $this->passwordResetTokenRepository->removeExpiredTokens();
        $this->outputLine('Expired password reset tokens have been removed.');
    }

}
