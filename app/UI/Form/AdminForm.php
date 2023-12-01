<?php

declare(strict_types=1);

namespace App\UI\Form;

use Nette;
use Nette\Application\UI\Form;
use Nette\Forms\ControlGroup;
use Nette\Utils\Html;
use App\UI\Form\Control\AjaxSubmitButton;
use App\UI\Form\Control\CodebaseCheckbox;
use App\UI\Form\Control\CodebaseRadioList;
use App\UI\Form\Control\ColorpickerInput;
use App\UI\Form\Control\DateRangeInput;
use App\UI\Form\Control\DateTimeRangeInput;
use App\UI\Form\Control\MarkdownEditorInput;
use App\UI\Form\Control\PriceInput;
use App\UI\Form\Control\SwitchCheckbox;
use App\UI\Form\Control\TextEditorInput;
use App\UI\Form\Renderer\DashkitRenderer;

class AdminForm extends Form
{
    const LABEL_SELECT_PROMPT = 'Vybrať možnosť';

    public function __construct(Nette\ComponentModel\IContainer $parent = null, string $name = null)
    {
        parent::__construct($parent, $name);

        $this->setRenderer(new DashkitRenderer());
        $this->addProtection('Prosím opakujte akciu, CSRF protekcia formulára expirovala');
    }

    public function addCodebaseRadioList(string $name, $label = null, array $items = null): CodebaseRadioList
    {
        return $this[$name] = new CodebaseRadioList($label, $items);
    }

    public function addCodebaseCheckbox(string $name, $caption = null): CodebaseCheckbox
    {
        return $this[$name] = new CodebaseCheckbox($caption);
    }

    public function addSwitchCheckbox(string $name, $caption = null): SwitchCheckbox
    {
        return $this[$name] = new SwitchCheckbox($caption);
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

    public function addMarkdownEditor(string $name, $label = null, int $cols = null, int $rows = null): MarkdownEditorInput
    {
        return $this[$name] = (new MarkdownEditorInput($label))
            ->setHtmlAttribute('size', $cols)->setHtmlAttribute('rows', $rows);
    }

    public function addAjaxSubmit(string $name, $caption = null): AjaxSubmitButton
    {
        return $this[$name] = new AjaxSubmitButton($caption);
    }

    public function addGroup($caption = null, bool $setAsCurrent = true, string $htmlId = null): ControlGroup
    {
        $group = parent::addGroup($caption, $setAsCurrent);
        
        $group->setOption('container', Html::el('div')->setAttribute('id', $htmlId));
        
        return $group;
    }
}
