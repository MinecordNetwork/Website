<?php

declare(strict_types=1);

namespace Minecord\Bridge\Nette\Thumbnail;

use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;

class ThumbnailExtension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('thumbnail'))
			->setFactory(ThumbnailGenerator::class, [
				'wwwDir' => $builder->parameters['wwwDir'],
				'thumbPathMask' => 'img/thumbs/{dirname}/{filename}-{width}x{height}.{extension}',
				'placeholder' => 'https://dummyimage.com/{width}x{height}/efefef/f00&text=Obrazok+nenajdeny'
			]);

		/** @var FactoryDefinition $latteFactory */
		$latteFactory = $this->getContainerBuilder()->getDefinitionByType(ILatteFactory::class);

		$latteFactory->getResultDefinition()->addSetup('addFilter', ['thumbnail', [$this->prefix('@thumbnail'), 'thumbnail']]);
		$latteFactory->getResultDefinition()->addSetup('addFilter', ['thumbnailRelative', [$this->prefix('@thumbnail'), 'thumbnailRelative']]);
	}
}
