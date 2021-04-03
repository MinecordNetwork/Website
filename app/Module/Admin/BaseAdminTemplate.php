<?php

declare(strict_types=1);

namespace App\Module\Admin;

use App\Model\User\User;
use Nette\Bridges\ApplicationLatte\Template;

abstract class BaseAdminTemplate extends Template
{
    public array $flashes;
    public bool $isAuthPresenter;
    public ?User $user;

    public string $javaScriptTag = '';
    public string $styleSheetsTag = '';
}
