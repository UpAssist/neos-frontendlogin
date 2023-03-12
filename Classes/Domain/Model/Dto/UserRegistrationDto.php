<?php
namespace UpAssist\Neos\FrontendLogin\Domain\Model\Dto;

use Doctrine\Common\Collections\Collection;
use Neos\Neos\Domain\Model\User;
use Neos\Flow\Annotations as Flow;
use Neos\Party\Domain\Model\ElectronicAddress;

/**
 * Class UserRegistrationDto
 * @package UpAssist\Neos\FrontendLogin\Domain\Model\Dto
 */
class UserRegistrationDto
{
    /**
     * @var User
     * @Flow\Validate(type="NotEmpty")
     */
    protected $user;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     */
    protected $username;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     */
    protected $password;

    /**
     * @var \Doctrine\Common\Collections\Collection<\Neos\Party\Domain\Model\ElectronicAddress>
     */
    protected $electronicAddresses;

    /**
     * @var string
     */
    protected $roleIdentifier = 'UpAssist.Neos.FrontendLogin:User';

    /**
     * @return \Doctrine\Common\Collections\Collection<\Neos\Party\Domain\Model\ElectronicAddress>
     */
    public function getElectronicAddresses(): Collection | null
    {
        return $this->electronicAddresses;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection<\Neos\Party\Domain\Model\ElectronicAddress> $electronicAddresses
     */
    public function setElectronicAddresses(Collection $electronicAddresses): void
    {
        $this->getUser()->setElectronicAddresses($electronicAddresses);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRoleIdentifier(): string
    {
        return $this->roleIdentifier;
    }

    /**
     * @param string $roleIdentifier
     */
    public function setRoleIdentifier(string $roleIdentifier): void
    {
        $this->roleIdentifier = $roleIdentifier;
    }

}
