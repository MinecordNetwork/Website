<?php

declare(strict_types=1);

namespace Minecord\UI\Form\Control;

use Nette\Forms\Controls\TextArea;

class MarkdownEditorInput extends TextArea
{
	public function getValue()
	{
		if (strip_tags($this->value) === '') {
			return null;
		}

		return $this->value;
	}
}
