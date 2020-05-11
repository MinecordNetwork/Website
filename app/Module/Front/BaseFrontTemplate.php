<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\Admin\Admin;
use Minecord\Model\System\System;
use Nette\Bridges\ApplicationLatte\Template;

class BaseFrontTemplate extends Template
{
	public System $system;
	public ?Admin $admin;
	public string $locale;
	public int $assetVersion;
}
