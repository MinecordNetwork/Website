<?php

declare(strict_types=1);

namespace App\Model\System;

class SystemProvider
{
    private ?System $system = null;

    public function __construct(
        private SystemFacade $systemFacade
    ) {}

    public function provide(): System
    {
        return $this->system === null ? $this->system = $this->systemFacade->get() : $this->system;
    }
}
