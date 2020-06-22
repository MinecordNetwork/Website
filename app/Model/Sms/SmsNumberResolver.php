<?php

declare(strict_types=1);

namespace Minecord\Model\Sms;

class SmsNumberResolver
{
	public static function resolve(float $price): string 
	{
		if ($price > 99) {
			return '90733';
			
		} else if ($price > 20) {
			return sprintf('90333%s', $price);
		}
		
		return '8877';
	}
}
