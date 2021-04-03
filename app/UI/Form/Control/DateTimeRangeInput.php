<?php

declare(strict_types=1);

namespace App\UI\Form\Control;

use DateTime;

class DateTimeRangeInput extends DateRangeInput
{
    public static function format(DateTime $from, DateTime $to): string
    {
        return $from->format('d.m.Y H:i') . ' - ' . $to->format('d.m.Y H:i');
    }
}
