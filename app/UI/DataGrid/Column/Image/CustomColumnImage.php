<?php

declare(strict_types=1);

namespace Minecord\UI\DataGrid\Column\Image;

use Latte\Engine;
use Latte\Loaders\StringLoader;
use Minecord\Model\Image\Image;
use Ublaboo\DataGrid\Column\ColumnText;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Exception\DataGridColumnRendererException;
use Ublaboo\DataGrid\Row;

class CustomColumnImage extends ColumnText
{
	private Engine $latte;
	private int $height;
	
	public function __construct(DataGrid $grid, string $key, string $column, string $name, int $height, Engine $latte)
	{
		parent::__construct($grid, $key, $column, $name);
		$this->setTemplateEscaping(false);
		$this->height = $height;
		$this->latte = $latte;
		$this->latte->setLoader(new StringLoader([
			'main' => sprintf('<div class="items-push js-gallery"><a class="img-link img-link-zoom-in img-thumb img-lightbox" href="{$publicPath}"><img style="max-height:%spx" class="img-fluid img-responsive" src="{$publicPath|thumbnail: 150, 150}"/></a></div>', $this->height)
		]));
	}

	public function render(Row $row)
	{
		try {
			return $this->useRenderer($row);
		} catch (DataGridColumnRendererException $e) {
		}
		
		$image = $this->getColumnValue($row);
		
		if ($image === null) {
			return '<i class="text-muted fe fe-image" title="Bez obrázka"></i>';
		}

		return $this->latte->renderToString('main', [
			'publicPath' => $image instanceof Image ? $image->getPublicPath() : $image
		]);
	}
}
