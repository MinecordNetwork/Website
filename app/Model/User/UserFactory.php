<?php

declare(strict_types=1);

namespace App\Model\User;

use Nette\Security\Passwords;
use Ramsey\Uuid\Uuid;

class UserFactory
{
    public function __construct(
        private Passwords $passwords
    ) {}

    public function create(UserData $userData): User
    {
        return new User(Uuid::uuid4(), $userData, $this->passwords);
    }
}
