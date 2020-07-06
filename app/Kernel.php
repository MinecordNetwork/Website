<?php

declare(strict_types=1);

namespace Minecord;

use Nette\Configurator;

class Kernel
{
	public static function boot(): Configurator
	{
		$configurator = new Configurator;
		
		$debugMode = substr(dirname(__FILE__), -7) !== 'www/app';

		$configurator->setDebugMode($debugMode)
			->setTimeZone('Europe/Prague')
			->setTempDirectory(__DIR__ . '/../temp');

		$configurator->addConfig(__DIR__ . '/../config/common.neon');

		$configurator->addConfig(__DIR__ . '/../config/credentials_dev.neon');
		if (!$debugMode) {
			$configurator->addConfig(__DIR__ . '/../config/credentials_prod.neon');
		}

		$configurator->enableTracy(__DIR__ . '/../log');

		return $configurator;
	}
}
