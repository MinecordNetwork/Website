<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid;

use Nette\Bridges\ApplicationLatte\ILatteFactory;

class DataGridFactory
{
	private ILatteFactory $latteFactory;

	public function __construct(ILatteFactory $latteFactory)
	{
		$this->latteFactory = $latteFactory;
	}

	public function create(): CustomDataGrid
	{
		$grid = new CustomDataGrid($this->latteFactory);

		//DataGrid::$iconPrefix = 'mdi mdi-';

		//$grid->setTemplateFile(__DIR__ . '/@Templates/datagrid.latte');

		return $grid;
	}
}
