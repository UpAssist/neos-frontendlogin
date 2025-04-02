<?php
namespace UpAssist\Neos\FrontendLogin\Domain\Model;

/*
 * This file is part of the UpAssist.Neos.FrontendLogin package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Neos\Flow\Security\Account;

/**
 * @Flow\Entity
 */
class PasswordResetToken
{

    /**
     * @var string
     * @ORM\Column(nullable=false)
     */
    protected string $token;

    /**
     * @var Account
     * @ORM\ManyToOne()
     * @ORM\Column(nullable=false)
     */
    protected Account $account;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    protected \DateTimeImmutable $createdAt;

    public function __construct(string $token = null, Account $account = null) {
        if ($token && $account) {
            $this->setToken($token);
            $this->setAccount($account);
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * @param Account $account
     * @return void
     */
    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
