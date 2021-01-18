<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, pre-dispatch and render `<input>` HTML element 
 *				   with types `text` and types `email`, `password`, 
 *				   `search`, `tel` and `url` in extended class. `Text` 
 *				   field and it's extended fields could have their own 
 *				   validator(s) to check submitted value for 
 *				   min length/max length/pattern and some of extended 
 *				   classes also dangerous characters in submitted 
 *				   text value(s). But it don't prevent SQL inject attacks
 *				   and more.
 */
class		Text 
extends		\MvcCore\Ext\Forms\Field 
implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
			\MvcCore\Ext\Forms\Fields\ILabel,
			\MvcCore\Ext\Forms\Fields\IPattern, 
			\MvcCore\Ext\Forms\Fields\IMinMaxLength,
			\MvcCore\Ext\Forms\Fields\IDataList {

	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Label;
	use \MvcCore\Ext\Forms\Field\Props\Pattern;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxLength;
	use \MvcCore\Ext\Forms\Field\Props\DataList;
	use \MvcCore\Ext\Forms\Field\Props\AutoComplete;
	use \MvcCore\Ext\Forms\Field\Props\PlaceHolder;
	use \MvcCore\Ext\Forms\Field\Props\Size;
	use \MvcCore\Ext\Forms\Field\Props\SpellCheck;
	use \MvcCore\Ext\Forms\Field\Props\InputMode;
	
	/**
	 * MvcCore Extension - Form - Field - Text - version:
	 * Comparison by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0';

	/**
	 * Possible values: `text` and `email`, `password`, `search`, `tel` and `url` in extended class.
	 * @var string
	 */
	protected $type = 'text';

	/**
	 * Validators: 
	 * - `SafeString` - remove from submitted value base ASCII characters from 0 to 31 incl. 
	 *					(first column) and escape special characters: `& " ' < > | = \ %`.
	 *					This validator is not prevent SQL inject attacks!
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['SafeString'/*, 'MinMaxLength', 'Pattern'*/];

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` after field
	 * is added into form instance by `$form->AddField();` method. Do not 
	 * use this method even if you don't develop any form field.
	 * - Check if field has any name, which is required.
	 * - Set up form and field id attribute by form id and field name.
	 * - Set up required.
	 * - Set up translate boolean property.
	 * - Set up `Pattern` validator, if any `pattern` property value defined.
	 * - Set up min/max length validator if necessary.
	 * @param \MvcCore\Ext\Form $form
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\Text
	 */
	public function SetForm (\MvcCore\Ext\IForm $form) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		parent::SetForm($form);
		$this->setFormPattern();
		$this->setFormMinMaxLength();
		return $this;
	}

	/**
	 * Return field specific data for validator.
	 * @param array $fieldPropsDefaultValidValues 
	 * @return array
	 */
	public function & GetValidatorData ($fieldPropsDefaultValidValues = []) {
		$result = [
			'pattern'	=> $this->pattern,
			'minLength'	=> $this->minLength, 
			'maxLength'	=> $this->maxLength, 
		];
		if ($this->list !== NULL) {
			$result['list'] = $this->list;
			$listField = $this->form->GetField($this->list);
			if ($listField instanceof \MvcCore\Ext\Forms\Fields\IOptions) 
				$result['options'] = $listField->GetOptions();
		}
		return $result;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method even if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Translate placeholder text if necessary.
	 * - Set up `inputmode` field attribute if necessary.
	 * - Set up tab-index if necessary.
	 * @return void
	 */
	public function PreDispatch () {
		parent::PreDispatch();
		$this->preDispatchPlaceHolder();
		$this->preDispatchInputMode();
		$this->preDispatchTabIndex();
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Forms\Field\Rendering` 
	 * in rendering process. Do not use this method even if you don't develop any form field.
	 * 
	 * Render control tag only without label or specific errors.
	 * @return string
	 */
	public function RenderControl () {
		$attrsStr = $this->renderControlAttrsWithFieldVars([
			'pattern',
			'minLength', 'maxLength',
			'list',
			'autoComplete',
			'placeHolder',
			'size',
			'spellCheck',
			'inputMode',
		]);
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= (strlen($attrsStr) > 0 ? ' ' : '')
				. 'form="' . $this->form->GetId() . '"';
		$formViewClass = $this->form->GetViewClass();
		/** @var $templates \stdClass */
		$templates = static::$templates;
		return $formViewClass::Format($templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name,
			'type'		=> $this->type,
			'value'		=> htmlspecialchars_decode(htmlspecialchars($this->value, ENT_QUOTES), ENT_QUOTES),
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
	}
}
