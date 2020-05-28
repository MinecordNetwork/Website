<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$isApi = substr($_SERVER['REQUEST_URI'], 0, 4) === '/api';

Minecord\Kernel::boot()
	->createContainer()
	->getByType($isApi ? Apitte\Core\Application\IApplication::class : Nette\Application\Application::class)
	->run();
