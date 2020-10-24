<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Product\Grid;

use Minecord\Model\Product\ProductFacade;
use Minecord\UI\DataGrid\DataGridFactory;
use Ublaboo\DataGrid\DataGrid;

class ProductGridFactory
{
	private ProductFacade $productFacade;
	private DataGridFactory $dataGridFactory;

	public function __construct(
		ProductFacade $productFacade,
		DataGridFactory $dataGridFactory
	) {
		$this->productFacade = $productFacade;
		$this->dataGridFactory = $dataGridFactory;
	}

	public function create(): DataGrid
	{
		$grid = $this->dataGridFactory->create();

		$grid->setStrictSessionFilterValues();
		$grid->setShowSelectedRowsCount(false);
		$grid->setItemsPerPageList([20, 50, 100, 200]);

		$queryBuilder = $this->productFacade->getQueryBuilderForDataGrid();

		$grid->setDataSource($queryBuilder);

		$grid->addColumnImage('thumbnail', 'Thumbnail');

		$grid->addColumnLink('nameEnglish', 'Name [EN]', 'edit')
			->setSortable()
			->setFilterText('nameEnglish');

		$grid->addColumnLink('nameCzech', 'Name [CZ]', 'edit')
			->setSortable()
			->setFilterText('nameCzech');
		
		return $grid;
	}
}
