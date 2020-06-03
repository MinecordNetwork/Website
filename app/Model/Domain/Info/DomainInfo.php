<?php

declare(strict_types=1);

namespace Minecord\Model\Domain\Info;

class DomainInfo
{
	private string $primaryDomain;
	private string $secondaryDomain;

	public function __construct(string $primaryDomain, string $secondaryDomain)
	{
		$this->primaryDomain = $primaryDomain;
		$this->secondaryDomain = $secondaryDomain;
	}

	public function getPrimaryDomain(): string
	{
		return $this->primaryDomain;
	}

	public function getSecondaryDomain(): string
	{
		return $this->secondaryDomain;
	}
}
