<?php

declare(strict_types=1);

namespace Minecord\Module\Admin\Article\Grid;

use Minecord\Model\Article\ArticleFacade;
use Minecord\UI\DataGrid\DataGridFactory;
use Ublaboo\DataGrid\DataGrid;

class ArticleGridFactory
{
	private ArticleFacade $articleFacade;
	private DataGridFactory $dataGridFactory;

	public function __construct(
		ArticleFacade $articleFacade, 
		DataGridFactory $dataGridFactory
	) {
		$this->articleFacade = $articleFacade;
		$this->dataGridFactory = $dataGridFactory;
	}

	public function create(): DataGrid
	{
		$grid = $this->dataGridFactory->create();

		$grid->setStrictSessionFilterValues();
		$grid->setShowSelectedRowsCount(false);
		$grid->setItemsPerPageList([20, 50, 100, 200]);

		$queryBuilder = $this->articleFacade->getQueryBuilderForDataGrid();
		
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
