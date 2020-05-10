<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid\Column\Status;

use Ublaboo\DataGrid\Column\ColumnStatus;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridColumnStatusException;
use Minecord\UI\DataGrid\Column\Option\CustomOption;

class CustomColumnStatus extends ColumnStatus
{
	public function __construct(
		DataGrid $grid,
		string $key,
		string $column,
		string $name
	) {
		parent::__construct($grid, $key, $column, $name);
		$this->setTemplate(__DIR__ . '/../../@Templates/columnStatus.latte');
	}

	public function addOption($value, string $text): CustomOption
	{
		if (!is_scalar($value)) {
			throw new DataGridColumnStatusException('Option value has to be scalar');
		}

		$option = new CustomOption($this, $value, $text);

		$this->options[] = $option;

		return $option;
	}
}
