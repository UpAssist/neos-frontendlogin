<?php

namespace UpAssist\Neos\FrontendLogin\Domain\Model\Dto;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Model\User;

class NewPasswordDto
{
    /**
     * @var array $password
     * @Flow\Validate(argumentName="password", type="\Neos\Neos\Validation\Validator\PasswordValidator", options={ "allowEmpty"=0, "minimum"=1, "maximum"=255 })
     */
    protected array $password;

    /**
     * @var User $user
     * @Flow\Validate(type="NotEmpty")
     */
    protected User $user;

    /**
     * @return array
     */
    public function getPassword(): array
    {
        return $this->password;
    }

    /**
     * @param array $password
     */
    public function setPassword(array $password): void
    {
        $this->password = $password;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}
