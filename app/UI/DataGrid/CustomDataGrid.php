<?php

declare(strict_types=1);

namespace App\UI\DataGrid;

use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\ComponentModel\IContainer;
use App\UI\DataGrid\Column\Link\CustomColumnLink;
use App\UI\DataGrid\Column\Text\CustomColumnText;
use Ublaboo\DataGrid\DataGrid;
use App\UI\DataGrid\Column\Image\CustomColumnImage;
use App\UI\DataGrid\Column\Status\CustomColumnStatus;
use App\UI\DataGrid\DataSource\CustomDoctrineDataSource;
use Ublaboo\DataGrid\DataSource\NetteDatabaseTableDataSource;

class CustomDataGrid extends DataGrid
{
    public function __construct(private LatteFactory $latteFactory, ?IContainer $parent = null, ?string $name = null)
    {
        parent::__construct($parent, $name);
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
