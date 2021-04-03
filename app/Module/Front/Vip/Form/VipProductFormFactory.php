<?php

declare(strict_types=1);

namespace App\Module\Front\Vip\Form;

use Archette\FormUtils\Renderer\BootstrapRenderer;
use Nette\Application\UI\Form;
use Ramsey\Uuid\UuidInterface;

class VipProductFormFactory
{
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
