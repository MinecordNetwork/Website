<?php

declare(strict_types=1);

namespace App\Model\Vote;

use DateTime;

class Vote
{
    public int $id;
    public string $nickname;
    public DateTime $createdAt;
}
