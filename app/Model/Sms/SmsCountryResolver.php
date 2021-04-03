<?php

declare(strict_types=1);

namespace App\Model\Sms;

use Nette\Utils\Strings;

class SmsCountryResolver
{
    public static function resolve(string $shortcode): string 
    {
        return Strings::startsWith($shortcode, '8877') ? 'SK' : 'CZ';
    }
}
