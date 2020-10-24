<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid\Column\Text;

use Ublaboo\DataGrid\Column\ColumnText;

class CustomColumnText extends ColumnText
{
	/**
	 * @var array
	 */
	protected $editableElement = ['input', ['class' => 'form-control']];
}
