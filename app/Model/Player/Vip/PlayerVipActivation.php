<?php

declare(strict_types=1);

namespace App\Model\Player\Vip;

use Nette\Utils\DateTime;

class PlayerVipActivation
{
    public string $nickname;
    public DateTime $activatedAt;
}
