<?php

declare(strict_types=1);

namespace App\UI\DataGrid;

use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Ublaboo\DataGrid\Localization\SimpleTranslator;

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
        
        $grid->setColumnsHideable();
        
        $grid->setItemsPerPageList([20, 50, 100, 200, 500]);
        
        $grid->setStrictSessionFilterValues(false);

        $grid->useHappyComponents(false);

        $grid->setTranslator(new SimpleTranslator([
            'ublaboo_datagrid.no_item_found_reset' => 'Nenašli sa žiadne položky, filter môžete vynulovať',
            'ublaboo_datagrid.no_item_found' => 'Nenašli sa žiadne položky',
            'ublaboo_datagrid.here' => 'tu',
            'ublaboo_datagrid.items' => 'Položky',
            'ublaboo_datagrid.all' => 'všetky',
            'ublaboo_datagrid.from' => 'z',
            'ublaboo_datagrid.reset_filter' => 'Resetovať filter',
            'ublaboo_datagrid.group_actions' => 'Hromadné akcie',
            'ublaboo_datagrid.show_all_columns' => 'Zobrazit všetky stĺpce',
            'ublaboo_datagrid.hide_column' => 'Skryť stĺpec',
            'ublaboo_datagrid.action' => 'Akcie',
            'ublaboo_datagrid.previous' => 'Predošlá',
            'ublaboo_datagrid.next' => 'Ďalšia',
            'ublaboo_datagrid.choose' => 'Vyberte',
            'ublaboo_datagrid.execute' => 'Vykonať',
            'Name' => 'Meno',
            'Inserted' => 'Zapísané'
        ]));

        return $grid;
    }
}
