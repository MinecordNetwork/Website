<?php

declare(strict_types=1);

namespace App\UI\Form\Renderer;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\CheckboxList;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Utils\Html;
use App\UI\Form\Control\AjaxSubmitButton;
use App\UI\Form\Control\ColorpickerInput;
use App\UI\Form\Control\DateInput;
use App\UI\Form\Control\DateRangeInput;
use App\UI\Form\Control\DateTimeInput;
use App\UI\Form\Control\DateTimeRangeInput;
use App\UI\Form\Control\MarkdownEditorInput;
use App\UI\Form\Control\SwitchCheckbox;
use App\UI\Form\Control\TextEditorInput;
use App\UI\Form\Control\TimeInput;

class DashkitRenderer extends BootstrapRenderer
{
    protected function generateControls(BaseControl $control): Html
    {
        if ($control instanceof SwitchCheckbox) {
            $wrapper = Html::el('div')->appendAttribute('class', 'col-12');
            $switch = Html::el('div')->appendAttribute('class', 'form-switch');

            $input = $control->getControlPart();
            $input->setAttribute('class', 'form-check-input');

            $label = Html::el('label', ['class' => 'ml-2 form-check-label', 'for' => $control->getHtmlId()]);
            $label->addText($control->getCaption());

            $switch->addHtml($input);
            $switch->addHtml($label);

            $wrapper->addHtml($switch);

            return $wrapper;

        } else if ($control instanceof Checkbox) {
            $wrapper = Html::el('div')->appendAttribute('class', 'col-12');

            $input = $control->getControlPart();
            $input->setAttribute('class', 'form-check-input');

            $label = Html::el('label', ['class' => 'ml-1 form-check-label', 'for' => $control->getHtmlId()]);
            $label->addText($control->getCaption());

            $wrapper->addHtml($input);
            $wrapper->addHtml($label);

            return $wrapper;

        } else if ($control instanceof RadioList || $control instanceof CheckboxList) {
            $wrapper = Html::el('div')->appendAttribute('class', 'col-12');

            foreach ($control->getItems() as $key => $labelTitle) {
                if ($control instanceof RadioList) {
                    $container = Html::el('div', ['class' => 'mb-2']);
                } else {
                    $container = Html::el('div', ['class' => 'mb-2']);
                }

                $input = $control->getControlPart($key);
                $label = $control->getLabelPart($key);

                $label->setAttribute('class', 'form-check-label');
                $input->setAttribute('class', 'form-check-input');

                $container->addHtml($input);
                $container->addHtml($label);

                $wrapper->addHtml($container);
            }

            return $wrapper;
        } else if ($control instanceof SwitchCheckbox) {
            $wrapper = Html::el('div')->appendAttribute('class', 'col-12 form-check');

            $input = $control->getControlPart();
            $input->setAttribute('class', 'form-check-input');

            $label = Html::el('label', ['class' => 'form-check-label', 'for' => $control->getHtmlId()]);
            $label->addText($control->getCaption());

            $wrapper->addHtml($input);
            $wrapper->addHtml($label);

            return $wrapper;
        }

        return parent::generateControls($control);
    }

    protected function controlsInit(): void
    {
        if ($this->controlsInit) {
            return;
        }

        parent::controlsInit();

        foreach ($this->form->getControls() as $control) {
            if ($control instanceof AjaxSubmitButton) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'ajax btn btn-primary');

            } elseif ($control instanceof SubmitButton) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'btn btn-primary');

            } elseif ($control instanceof ColorpickerInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-control bg-white')
                    ->setAttribute('type', 'color')
                    ->setAttribute('format', 'hex');

            } elseif ($control instanceof DateTimeInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-control bg-white flatpickr-datetime')
                    ->setAttribute('data-enable-time', 'true');

            } elseif ($control instanceof DateInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-control bg-white flatpickr-date');

            } elseif ($control instanceof DateTimeRangeInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-control bg-white flatpickr-datetime')
                    ->setAttribute('data-enable-time', 'true')
                    ->setAttribute('data-mode', 'range');

            } elseif ($control instanceof DateRangeInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-control bg-white flatpickr-date')
                    ->setAttribute('data-mode', 'range');

            } elseif ($control instanceof TimeInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-control bg-white flatpickr-time text-left')
                    ->setAttribute('data-date-format', 'H:i')
                    ->setAttribute('data-time_24hr', 'true')
                    ->setAttribute('data-enable-time', 'true')
                    ->setAttribute('data-no-calendar', 'true');

            } elseif ($control instanceof TextEditorInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'summernote');

            } elseif ($control instanceof MarkdownEditorInput) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'markdown');

            } elseif ($control instanceof SelectBox) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-select')
                    ->setAttribute('data-choices', '{"placeholder": "Vyberte možnosť", "removeItemButton": true}');

            } elseif ($control instanceof MultiSelectBox) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-select')
                    ->setAttribute('data-choices', '{"placeholder": "Vyberte možnosť", "removeItemButton": true}');

            } elseif ($control instanceof Checkbox) {
                $control->getControlPrototype()
                    ->setAttribute('class', 'form-check-input');
            }
        }
    }
}
