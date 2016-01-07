<?php
namespace UpAssist\Neos\FrontendLogin\Domain\Model\Dto;

use TYPO3\Neos\Domain\Model\User;
use TYPO3\Flow\Annotations as Flow;

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

}