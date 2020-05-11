<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\Admin\Admin;
use Minecord\Model\System\System;

class BaseFrontTemplate
{
	public System $system;
	public ?Admin $admin;
	public string $locale;
	public int $assetVersion;
}
