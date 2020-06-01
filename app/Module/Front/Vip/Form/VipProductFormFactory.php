<?php

declare(strict_types=1);

namespace Minecord\Module\Front\Vip\Form;

use Archette\FormUtils\Renderer\BootstrapRenderer;
use Minecord\Model\Product\ProductFacade;
use Nette\Application\UI\Form;
use Ramsey\Uuid\UuidInterface;

class VipProductFormFactory
{
	private ProductFacade $productFacade;

	public function __construct(
		ProductFacade $productFacade
	) {
		$this->productFacade = $productFacade;
	}

	public function create(UuidInterface $productId): Form
	{
		$form = new Form();
		
		$form->addHidden('product', (string) $productId)
			->setRequired();
		
		$form->addText('nickname', 'Nickname')
			->addRule(Form::PATTERN, 'Invalid nickname', '^(?=.*[A-Za-z0-9_-])[$!@{}[\]A-Za-z0-9_-]{1,16}$')
			->setRequired();
		
		$form->setRenderer(new BootstrapRenderer());

		return $form;
	}
}
