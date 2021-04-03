<?php

declare(strict_types=1);

namespace App\Module\Admin\Page\Grid;

use App\Model\Page\PageFacade;
use App\UI\DataGrid\DataGridFactory;
use Ublaboo\DataGrid\DataGrid;

class PageGridFactory
{
    public function __construct(
        private PageFacade $pageFacade,
        private DataGridFactory $dataGridFactory
    ) {}

    public function create(): DataGrid
    {
        $grid = $this->dataGridFactory->create();

        $grid->setStrictSessionFilterValues();
        $grid->setShowSelectedRowsCount(false);
        $grid->setItemsPerPageList([20, 50, 100, 200]);

        $queryBuilder = $this->pageFacade->getQueryBuilderForDataGrid();
        
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
