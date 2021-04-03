<?php

declare(strict_types=1);

namespace App\UI\Form\Control;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;

class PriceInput extends TextInput
{
    public function __construct($label = null, int $maxLength = null)
    {
        parent::__construct($label, $maxLength);
        
        $this->addRule(Form::FLOAT)
            ->addRule(Form::MIN, 'Neplatná hodnota', 0);
    }
    
    public function getRenderedValue(): ?string
    {
        return number_format((float) $this->value, 2, ',', '');
    }

    public function getValue()
    {
        $value = str_replace([
            ' ', 
            ','
        ], [
            '', 
            '.'
        ], $this->value);
        
        return round((float) $value, 2);
    }
}
