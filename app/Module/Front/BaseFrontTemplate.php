<?php

declare(strict_types=1);

namespace Minecord\Module\Front;

use Minecord\Model\Admin\Admin;

class BaseFrontTemplate
{
	public ?Admin $admin;
	public string $locale;
	public int $assetVersion;
}
