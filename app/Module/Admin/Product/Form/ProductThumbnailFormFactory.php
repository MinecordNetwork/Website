<?php

declare(strict_types=1);

namespace App\Module\Admin\Product\Form;

use App\UI\Form\AdminForm;
use Nette\Application\UI\Form;

class ProductThumbnailFormFactory
{
    public function create(): Form
    {
        $form = new AdminForm();

        $form->addUpload('image', 'Image')
            ->setHtmlAttribute('onChange', 'this.form.submit()')
            ->setRequired();

        return $form;
    }
}
