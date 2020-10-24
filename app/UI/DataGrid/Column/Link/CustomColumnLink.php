<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid\Column\Link;

use Ublaboo\DataGrid\Column\ColumnLink;
use Ublaboo\DataGrid\DataGrid;

class CustomColumnLink extends ColumnLink
{
	public function __construct(DataGrid $grid, string $key, string $column, string $name, string $href, array $params)
	{
		parent::__construct($grid, $key, $column, $name, $href, $params);
		
		$this->class = 'ajax';
		$this->setDataAttribute('naja-history', 'on');
	}
}
