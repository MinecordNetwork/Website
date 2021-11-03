<?php

declare(strict_types=1);

namespace App;

use Nette\Configurator;

class Bootstrap
{
    public static function boot(): Configurator
    {
        $configurator = new Configurator;
        
        $debugMode = !str_ends_with(dirname(__FILE__), 'www/app');

        $configurator->setDebugMode($debugMode)
            ->setTimeZone('Europe/Prague')
            ->setTempDirectory(__DIR__ . '/../temp');

        $configurator->addConfig(__DIR__ . '/../config/main.neon');

        $configurator->addConfig(__DIR__ . '/../config/parameters_dev.neon');
        if (!$debugMode) {
            $configurator->addConfig(__DIR__ . '/../config/parameters_prod.neon');
        }

        $configurator->enableTracy(__DIR__ . '/../log');

        return $configurator;
    }
}
