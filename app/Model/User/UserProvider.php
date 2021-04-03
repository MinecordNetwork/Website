<?php

declare(strict_types=1);

namespace App\Model\User;

use App\Model\Session\SessionProvider;

class UserProvider
{
    private SessionProvider $sessionProvider;

    public function __construct(SessionProvider $sessionProvider)
    {
        $this->sessionProvider = $sessionProvider;
    }

    public function provide(): ?User
    {
        return $this->sessionProvider->provide()->getUser();
    }
}
