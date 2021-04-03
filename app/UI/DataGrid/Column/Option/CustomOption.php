<?php

declare(strict_types=1);

namespace App\UI\DataGrid\Column\Option;

use Ublaboo\DataGrid\Status\Option;

class CustomOption extends Option
{
    private ?string $backgroundColor = null;
    
    public function setBackgroundColor(string $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }
}
