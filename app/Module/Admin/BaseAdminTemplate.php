<?php

declare(strict_types=1);

namespace Minecord\Module\Admin;

use Minecord\Model\User\User;
use Nette\Bridges\ApplicationLatte\Template;

class BaseAdminTemplate extends Template
{
	public array $flashes;
	public bool $isAuthPresenter;
	public ?User $user;
}
