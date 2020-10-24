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

		$grid->addColumnLink('titleEnglish', 'Title [EN]', 'edit')
			->setSortable()
			->setFilterText('titleEnglish');

		$grid->addColumnLink('titleCzech', 'Title [CZ]', 'edit')
			->setSortable()
			->setFilterText('titleCzech');
		
		return $grid;
	}
}
