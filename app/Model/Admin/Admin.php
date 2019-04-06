<?php

declare(strict_types=1);

namespace Minecord\Model\Admin;

use DateTime;

class Admin
{
    /**
     * @ORM\Column(type="string", length=47)
     * @var string
     */
    private $display_name;

    /**
     * @ORM\Column(type="string", length=63, unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @var string
     */
    private $first_ip;

    /**
     * @ORM\Column(type="string", length=45, unique=true)
     * @var string
     */
    private $last_ip;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $first_login_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $last_login_at;

    public function getDisplayName(): string
    {
        return $this->display_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstIp(): string
    {
        return $this->first_ip;
    }

    public function getLastIp(): string
    {
        return $this->last_ip;
    }

    public function getFirstLoginAt(): DateTime
    {
        return $this->first_login_at;
    }

    public function getLastLoginAt(): DateTime
    {
        return $this->last_login_at;
    }
}