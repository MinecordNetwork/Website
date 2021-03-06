<?php

declare(strict_types=1);

namespace App\Module\Admin\Article\Form;

use Archette\FormUtils\Mapper\FormData;
use App\Model\Article\Article;
use App\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class ArticleFormFactory
{
    public function create(Article $article = null): Form
    {
        $form = new AdminForm();
        
        if ($article !== null) {
            $form->addText('id', 'ID')
                ->setDefaultValue((string) $article->getId())
                ->setHtmlAttribute('readonly', true);
        }
        
        $form->addText('titleEnglish', 'Title 🇺🇸')
            ->setRequired();
        
        $form->addTextEditor('editorialEnglish', 'Editorial 🇺🇸')
            ->setRequired();
        
        $form->addTextEditor('contentEnglish', 'Content 🇺🇸')
            ->setRequired();

        $form->addText('titleCzech', 'Title 🇨🇿')
            ->setRequired();

        $form->addTextEditor('editorialCzech', 'Editorial 🇨🇿')
            ->setRequired();
        
        $form->addTextEditor('contentCzech', 'Content 🇨🇿')
            ->setRequired();

        $form->addAjaxSubmit('submit', 'Uložiť príspevok');
        
        if ($article !== null) {
            $form->setDefaults(new FormData($article->getData()));
        }
        
        return $form;
    }
}
