<?php

declare(strict_types=1);

namespace App\Module\Admin\Page\Form;

use Archette\FormUtils\Mapper\FormData;
use App\Model\Page\Page;
use App\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class PageFormFactory
{
    public function create(Page $page = null): Form
    {
        $form = new AdminForm();
        
        if ($page !== null) {
            $form->addText('id', 'ID')
                ->setDefaultValue((string) $page->getId())
                ->setHtmlAttribute('readonly', true);
        }
        
        $form->addText('titleEnglish', 'Title 🇺🇸')
            ->setRequired();
        
        $form->addTextEditor('contentEnglish', 'Content 🇺🇸')
            ->setRequired();

        $form->addText('titleCzech', 'Title 🇨🇿')
            ->setRequired();
        
        $form->addTextEditor('contentCzech', 'Content 🇨🇿')
            ->setRequired();

        $form->addAjaxSubmit('submit', 'Uložiť stránku');
        
        if ($page !== null) {
            $form->setDefaults(new FormData($page->getData()));
        }
        
        return $form;
    }
}
