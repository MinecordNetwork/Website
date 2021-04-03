<?php

declare(strict_types=1);

namespace App\Module\Admin\Article\Form;

use App\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class ArticleThumbnailFormFactory
{
    public function create(): Form
    {
        $form = new AdminForm();

        $form->addUpload('image', 'Obrázok')
            ->setHtmlAttribute('onChange', 'this.form.submit()')
            ->setRequired();

        return $form;
    }
}
