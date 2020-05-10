<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Page\Grid;

use Minecord\Model\Page\PageFacade;
use Minecord\UI\DataGrid\DataGridFactory;
use Ublaboo\DataGrid\DataGrid;

class PageGridFactory
{
	private PageFacade $pageFacade;
	private DataGridFactory $dataGridFactory;

	public function __construct(
		PageFacade $pageFacade, 
		DataGridFactory $dataGridFactory
	) {
		$this->pageFacade = $pageFacade;
		$this->dataGridFactory = $dataGridFactory;
	}

	public function create(): DataGrid
	{
		$grid = $this->dataGridFactory->create();

		$grid->setStrictSessionFilterValues();
		$grid->setShowSelectedRowsCount(false);
		$grid->setItemsPerPageList([20, 50, 100, 200]);

		$queryBuilder = $this->pageFacade->getQueryBuilderForDataGrid();
		
		$grid->setDataSource($queryBuilder);

		$grid->addColumnImage('thumbnail', 'Thumbnail');

		$grid->addColumnText('titleEnglish', 'Title [EN]')
			->setSortable()
			->setFilterText('titleEnglish');

		$grid->addColumnText('titleCzech', 'Title [CZ]')
			->setSortable()
			->setFilterText('titleCzech');

		$grid->addAction('edit', 'Edit');

		return $grid;
	}
}
