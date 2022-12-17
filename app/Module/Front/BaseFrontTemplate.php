<?php

declare(strict_types=1);

namespace App\Module\Front;

use App\Model\Player\Player;
use App\Model\System\System;
use App\Model\User\User;
use Nette\Bridges\ApplicationLatte\Template;
use Rixafy\Currency\Currency;

abstract class BaseFrontTemplate extends Template
{
    public System $system;
    public string $dateTimeFormat;
    public string $dateFormat;
    public string $locale;
    public string $localeOther;
    public string $country;
    public Currency $currency;
    public int $cssBundleVersion;
    public int $jsBundleVersion;
    public array $flashes;
    public ?User $user;
    
    /** @var Player[] */
    public array $onlinePlayers = [];
}
