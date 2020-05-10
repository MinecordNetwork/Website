<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid;

use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\ComponentModel\IContainer;
use Ublaboo\DataGrid\DataGrid;
use Minecord\UI\DataGrid\Column\Image\CustomColumnImage;
use Minecord\UI\DataGrid\Column\Status\CustomColumnStatus;
use Minecord\UI\DataGrid\DataSource\CustomDoctrineDataSource;

class CustomDataGrid extends DataGrid
{
	private ILatteFactory $latteFactory;
	
	public function __construct(ILatteFactory $latteFactory, ?IContainer $parent = null, ?string $name = null)
	{
		parent::__construct($parent, $name);
		$this->latteFactory = $latteFactory;
	}

	public function addColumnStatus(string $key, string $name, ?string $column = null): CustomColumnStatus
	{
		$column = $column ?: $key;

		$columnStatus = new CustomColumnStatus($this, $key, $column, $name);
		$this->addColumn($key, $columnStatus);

		return $columnStatus;
	}

	public function addColumnImage(string $key, string $name, ?string $column = null, int $height = 50): CustomColumnImage
	{
		$column = $column ?: $key;

		$columnText = new CustomColumnImage($this, $key, $column, $name, $height, $this->latteFactory->create());
		$this->addColumn($key, $columnText);

		return $columnText;
	}

	public function setDataSource($source): DataGrid
	{
		return parent::setDataSource(new CustomDoctrineDataSource($source));
	}
}
