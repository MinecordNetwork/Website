<?php

declare(strict_types=1);

namespace Minecord\UI\Form\Renderer;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\SubmitButton;
use Nette\Forms\IControl;
use Nette\Utils\Html;
use Minecord\UI\Form\Control\AjaxSubmitButton;
use Minecord\UI\Form\Control\CodebaseCheckbox;
use Minecord\UI\Form\Control\CodebaseRadioList;
use Minecord\UI\Form\Control\ColorpickerInput;
use Minecord\UI\Form\Control\DateInput;
use Minecord\UI\Form\Control\DateRangeInput;
use Minecord\UI\Form\Control\DateTimeInput;
use Minecord\UI\Form\Control\DateTimeRangeInput;
use Minecord\UI\Form\Control\TextEditorInput;
use Minecord\UI\Form\Control\TimeInput;

class CodebaseRenderer extends BootstrapRenderer
{
	public function renderPair(IControl $control): string
	{
		/*if ($control instanceof ColorpickerInput) {
			bdump('test123');

			$pair = $this->getWrapper('pair container');

			$pair->addHtml(Html::el('label', ['class' => 'col-12'])->addText($control->getCaption()));

			$wrapper = Html::el('div')->appendAttribute('class', 'col-12');

			$colorpicker = Html::el('div', ['class' => 'js-colorpicker input-group', 'data-format' => 'hex']);

			$input = $control->getControlPart();
			$input->setAttribute('class', 'form-control bg-white colorpicker');

			$indicator = Html::el('div', ['class' => 'input-group-append']);
			$indicatorSpan = Html::el('span', ['class' => 'input-group-text colorpicker-input-addon']);
			$indicatorSpanPart = Html::el('i');

			$indicatorSpan->addHtml($indicatorSpanPart);
			$indicator->addHtml($indicatorSpan);

			$colorpicker->addHtml($input);
			$colorpicker->addHtml($indicator);

			$wrapper->addHtml($colorpicker);
			
			$pair->addHtml($wrapper);
			
			return $pair->toHtml();
			
		} else*/if ($control instanceof CodebaseCheckbox) {
			$radios = Html::el(null);
			$pair = $this->getWrapper('pair container');

			$pair->addHtml(Html::el('label', ['class' => 'col-12'])->addText($control->getCaption()));
			$pair->addHtml($this->generateControls($control));

			$radios->addHtml($pair);

			return $radios->render(0);
		}

		return parent::renderPair($control);
	}

	protected function generateControls(BaseControl $control): Html
	{
		if ($control instanceof CodebaseRadioList) {
			$wrapper = Html::el('div')->appendAttribute('class', 'col-12');

			foreach ($control->getItems() as $key => $labelTitle) {
				$input = $control->getControlPart($key);
				$label = $control->getLabelPart($key);

				$label->setAttribute('class', 'css-control css-control-primary css-radio');
				$input->setAttribute('class', 'css-control-input');

				$label->setHtml($input->toHtml() . Html::el('span', ['class' => 'css-control-indicator']) . $label->getText());

				$wrapper->addHtml($label);
			}

			return $wrapper;

		} else if ($control instanceof CodebaseCheckbox) {
			$wrapper = Html::el('div')->appendAttribute('class', 'col-12');

			$label = Html::el('label', ['class' => 'css-control css-control-success css-switch']);

			$input = $control->getControlPart();
			$input->setAttribute('class', 'css-control-input');

			$span = Html::el('span', ['class' => 'css-control-indicator']);

			$label->addHtml($input);
			$label->addHtml($span);

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
					->setAttribute('class', 'ajax btn btn-alt-primary');
				
			} elseif ($control instanceof SubmitButton) {
				$control->getControlPrototype()
					->setAttribute('class', 'btn btn-alt-primary');

			} elseif ($control instanceof ColorpickerInput) {
				$control->getControlPrototype()
					->setAttribute('class', 'form-control bg-white colorpicker');

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

			} elseif ($control instanceof SelectBox) {
				$control->getControlPrototype()
					->setAttribute('class', 'form-control js-select2');

			} elseif ($control instanceof MultiSelectBox) {
				$control->getControlPrototype()
					->setAttribute('class', 'form-control js-select2');
			}
		}
	}
}
