<?php

declare(strict_types=1);

namespace App\Model\Session;

use Ramsey\Uuid\Uuid;

class SessionFactory
{
    public function create(SessionData $data): Session
    {
        return new Session(Uuid::uuid4(), $data);
    }
}
