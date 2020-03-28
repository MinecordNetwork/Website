<?php

declare(strict_types=1);

namespace Minecord\Model\Session;

use Rixafy\Country\Country;
use Rixafy\IpAddress\IpAddress;

class SessionData
{
	public string $hash;
	public bool $isCrawler;
	public ?string $crawlerName = null;
	public IpAddress $ipAddress;
	public Country $country;
}
