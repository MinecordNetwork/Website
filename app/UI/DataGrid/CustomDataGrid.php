<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid;

use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\ComponentModel\IContainer;
use Minecord\UI\DataGrid\Column\Link\CustomColumnLink;
use Minecord\UI\DataGrid\Column\Text\CustomColumnText;
use Ublaboo\DataGrid\Column\ColumnLink;
use Ublaboo\DataGrid\DataGrid;
use Minecord\UI\DataGrid\Column\Image\CustomColumnImage;
use Minecord\UI\DataGrid\Column\Status\CustomColumnStatus;
use Minecord\UI\DataGrid\DataSource\CustomDoctrineDataSource;
use Ublaboo\DataGrid\DataSource\NetteDatabaseTableDataSource;

class CustomDataGrid extends DataGrid
{
	private ILatteFactory $latteFactory;
	
	public function __construct(ILatteFactory $latteFactory, ?IContainer $parent = null, ?string $name = null)
	{
		parent::__construct($parent, $name);
		$this->latteFactory = $latteFactory;
	}

	public function addColumnText(string $key, string $name, ?string $column = null): CustomColumnText
	{
		$column = $column ?: $key;

		$columnText = new CustomColumnText($this, $key, $column, $name);
		$this->addColumn($key, $columnText);

		return $columnText;
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
		$columnText->setFitContent();
		$columnText->setAlign('center');

		$this->addColumn($key, $columnText);

		return $columnText;
	}

	public function addColumnLink(string $key, string $name, ?string $href = null, ?string $column = null, ?array $params = null): CustomColumnLink
	{
		$column = $column ?: $key;
		$href = $href ?: $key;

		if ($params === null) {
			$params = [$this->primaryKey];
		}

		$columnLink = new CustomColumnLink($this, $key, $column, $name, $href, $params);
		$this->addColumn($key, $columnLink);

		return $columnLink;
	}

	public function setDataSource($source): DataGrid
	{
		if ($source instanceof NetteDatabaseTableDataSource) {
			return parent::setDataSource($source);
		}
		
		return parent::setDataSource(new CustomDoctrineDataSource($source));
	}
}
