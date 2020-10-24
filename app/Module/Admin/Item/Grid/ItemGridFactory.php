<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Item\Grid;

use Minecord\UI\DataGrid\DataGridFactory;
use Nette\Caching\Storage;
use Nette\Database\Connection;
use Nette\Database\Context;
use Nette\Database\Conventions\DiscoveredConventions;
use Nette\Database\Structure;
use Nette\Utils\Json;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\DataSource\NetteDatabaseTableDataSource;

class ItemGridFactory
{
	private Connection $connection;
	private DataGridFactory $dataGridFactory;
	private Storage $storage;

	public function __construct(
		Connection $connection,
		DataGridFactory $dataGridFactory, 
		Storage $storage
	) {
		$this->connection = $connection;
		$this->dataGridFactory = $dataGridFactory;
		$this->storage = $storage;
	}

	public function create(): DataGrid
	{
		$grid = $this->dataGridFactory->create();

		$grid->setStrictSessionFilterValues();
		$grid->setShowSelectedRowsCount(false);
		$grid->setItemsPerPageList([20, 50, 100, 200]);

		$structure = new Structure($this->connection, $this->storage);
		$conventions = new DiscoveredConventions($structure);

		$datasource = (new Context($this->connection, $structure, $conventions, $this->storage))->table('minecraft_item')->where('type', 'item');
		
		$grid->setDataSource(new NetteDatabaseTableDataSource($datasource, 'id'));
		
		$grid->addColumnLink('itemImage', 'Obrázok')
			->setRenderer(function ($data) {
				$json = Json::decode($data['item_stack_json']);
				$material = $json->material;
				return '<div class="items-push js-gallery"><a class="img-link img-link-zoom-in img-thumb img-lightbox" href="https://minecraftitemids.com/item/32/' . strtolower($material) . '.png"><img class="img-fluid img-responsive" src="https://minecraftitemids.com/item/32/' . strtolower($material) . '.png"/></a></div>';
			})
			->setTemplateEscaping(false);
		
		$grid->addColumnText('id', 'ID')
			->setSortable()
			->setFilterText('id');
		
		$grid->addColumnLink('name', 'Názov')
			->setSortable()
			->setFilterText('name');

		$grid->addColumnLink('material', 'Materiál')
			->setRenderer(function ($data) {
				$json = Json::decode($data['item_stack_json']);
				return $json->material;
			})
			->setTemplateEscaping(false)
			->setFilterText('item_stack_json');

		$grid->addColumnText('color', 'Farba')
			->setSortable()
			->setFilterText('color');

		return $grid;
	}
}