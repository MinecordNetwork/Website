<?php

declare(strict_types=1);

namespace Minecord\UI\Form\Control;

use DateTime;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;

class DateRangeInput extends TextInput
{
	public static function format(DateTime $from, DateTime $to): string
	{
		return $from->format('d.m.Y') . ' - ' . $to->format('d.m.Y');
	}

	public function loadHttpData(): void
	{
		$value = explode(' - ', $this->getHttpData(Form::DATA_LINE));
		if (count($value) > 1) {
			$this->setValue([
				'from' => new DateTime($value[0]),
				'to' => new DateTime($value[1])
			]);
		} else {
			$this->setValue([
				'from' => null,
				'to' => null
			]);
		}
	}

	public function setValue($value)
	{
		$this->value = $value;
		$this->rawValue = $value;

		return $this;
	}
}
