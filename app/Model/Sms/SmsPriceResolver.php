<?php

declare(strict_types=1);

namespace Minecord\Model\Sms;

use Nette\Utils\Strings;

class SmsPriceResolver
{
	public static function resolve(string $shortcode): float
	{
		if (Strings::startsWith($shortcode, '90333')) {
			return (int) str_replace('90333', '', $shortcode);
			
		} else if (Strings::startsWith($shortcode, '90733')) {
			return (int) str_replace('90733', '', $shortcode);
			
		} else if (Strings::startsWith($shortcode, '8877') && $shortcode !== '8877') {
			return (int) substr(str_replace('8877', '', $shortcode), 0, 2);
		}
		
		return 0;
	}
}
