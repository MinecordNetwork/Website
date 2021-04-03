<?php

declare(strict_types=1);

namespace App\UI\Form\Control;

use DateTime;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;

class DateInput extends TextInput
{
    public function loadHttpData(): void
    {
        $value = $this->getHttpData(Form::DATA_LINE);
        $this->setValue($value);
    }

    public function setValue($value)
    {
        if ($value instanceof DateTime) {
            $this->value = $value;
        } elseif (is_string($value)) {
            $this->value = new DateTime($value);
        }

        $this->rawValue = $value;

        return $this;
    }

    public function setDefaultValue($value)
    {
        $this->value = $value;
        $this->rawValue = $value;

        return $this;
    }
}
