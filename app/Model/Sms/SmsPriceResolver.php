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
	
	public static function reverse(float $price): string 
	{
		if ($price > 99) {
			return sprintf('90733%s', $price);
			
		} else if ($price > 20) {
			return sprintf('90333%s', $price);
		}
		
		return sprintf('8877%02d00', $price);
	}
}
