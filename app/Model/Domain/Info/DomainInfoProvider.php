<?php

declare(strict_types=1);

namespace Minecord\Model\Domain\Info;

use Nette\Utils\Strings;

class DomainInfoProvider
{
	private ?DomainInfo $domainInfo = null;
	
	public function provide(): DomainInfo
	{
		if ($this->domainInfo === null) {
			$firstDomain = '';
			$secondDomain = '';

			if (isset($_SERVER['SERVER_NAME'])) {
				$firstDomain = '//' . $_SERVER['SERVER_NAME'];
				if (Strings::contains($firstDomain, '.net')) {
					$secondDomain = str_replace('.net', '.cz', $firstDomain);
				} else {
					$secondDomain = str_replace('.cz', '.net', $firstDomain);
				}
			}
			
			$this->domainInfo = new DomainInfo($firstDomain, $secondDomain);
		}
		
		return $this->domainInfo;
	}
}
