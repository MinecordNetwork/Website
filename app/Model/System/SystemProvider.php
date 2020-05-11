<?php

declare(strict_types=1);

namespace Minecord\Model\System;

class SystemProvider
{
	private ?System $system = null;
	private SystemFacade $systemFacade;

	public function __construct(SystemFacade $systemFacade)
	{
		$this->systemFacade = $systemFacade;
	}

	public function provide(): System
	{
		return $this->system === null ? $this->system = $this->systemFacade->get() : $this->system;
	}
}
