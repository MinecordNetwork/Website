<?php

declare(strict_types=1);

namespace Minecord\UI\Form;

use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\ControlGroup;
use Nette\Utils\Html;
use Minecord\UI\Form\Control\AjaxSubmitButton;
use Minecord\UI\Form\Control\CodebaseCheckbox;
use Minecord\UI\Form\Control\CodebaseRadioList;
use Minecord\UI\Form\Control\ColorpickerInput;
use Minecord\UI\Form\Control\DateInput;
use Minecord\UI\Form\Control\DateTimeInput;
use Minecord\UI\Form\Control\DateRangeInput;
use Minecord\UI\Form\Control\DateTimeRangeInput;
use Minecord\UI\Form\Control\PriceInput;
use Minecord\UI\Form\Control\TextEditorInput;
use Minecord\UI\Form\Control\TimeInput;
use Minecord\UI\Form\Renderer\CodebaseRenderer;

class AdminForm extends Form
{
	const LABEL_SELECT_PROMPT = 'Vybrať možnosť';

	public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null)
	{
		parent::__construct($parent, $name);

		$this->setRenderer(new CodebaseRenderer());
	}

	public function addCodebaseRadioList(string $name, $label = null, array $items = null): CodebaseRadioList
	{
		return $this[$name] = new CodebaseRadioList($label, $items);
	}

	public function addCodebaseCheckbox(string $name, $caption = null): CodebaseCheckbox
	{
		return $this[$name] = new CodebaseCheckbox($caption);
	}

	public function addDate(string $name, $label = null, int $cols = null): DateInput
	{
		return $this[$name] = (new DateInput($label))
			->setHtmlAttribute('size', $cols);
	}

	public function addDateTime(string $name, $label = null, int $cols = null): DateTimeInput
	{
		return $this[$name] = (new DateTimeInput($label))
			->setHtmlAttribute('size', $cols);
	}

	public function addDateRange(string $name, $label = null, int $cols = null): DateRangeInput
	{
		return $this[$name] = (new DateRangeInput($label))
			->setHtmlAttribute('size', $cols);
	}

	public function addDateTimeRange(string $name, $label = null, int $cols = null): DateTimeRangeInput
	{
		return $this[$name] = (new DateTimeRangeInput($label))
			->setHtmlAttribute('size', $cols);
	}

	public function addTime(string $name, $label = null, int $cols = null, int $maxLength = null): TimeInput
	{
		return $this[$name] = (new TimeInput($label, $maxLength))
			->setHtmlAttribute('size', $cols);
	}

	public function addPrice(string $name, $label = null, int $cols = null, int $maxLength = null): PriceInput
	{
		return $this[$name] = (new PriceInput($label, $maxLength))
			->setHtmlAttribute('size', $cols);
	}

	public function addColorpicker(string $name, $label = null, int $cols = null, int $maxLength = null): ColorpickerInput
	{
		return $this[$name] = (new ColorpickerInput($label, $maxLength))
			->setHtmlAttribute('size', $cols);
	}

	public function addTextEditor(string $name, $label = null, int $cols = null, int $rows = null): TextEditorInput
	{
		return $this[$name] = (new TextEditorInput($label))
			->setHtmlAttribute('size', $cols)->setHtmlAttribute('rows', $rows);
	}

	public function addAjaxSubmit(string $name, $caption = null): AjaxSubmitButton
	{
		return $this[$name] = new AjaxSubmitButton($caption);
	}

	public function addGroup(string $caption = null, bool $setAsCurrent = true, string $htmlId = null): ControlGroup
	{
		$group = parent::addGroup($caption, $setAsCurrent);
		
		$group->setOption('container', Html::el('div')->setAttribute('id', $htmlId));
		
		return $group;
	}
}
