<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\System\System;
use Minecord\Model\User\User;
use Nette\Bridges\ApplicationLatte\Template;
use Rixafy\Currency\Currency;

class BaseFrontTemplate extends Template
{
	public System $system;
	public string $dateTimeFormat;
	public string $dateFormat;
	public string $locale;
	public string $country;
	public Currency $currency;
	public int $assetVersion;
	public array $flashes;
	public ?User $user;
}
