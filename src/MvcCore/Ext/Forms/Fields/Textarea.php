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
 * Responsibility: init, pre-dispatch and render `<textarea>` HTML element.
 *                 `Textarea` field has it's own validator(s) to check 
 *                 submitted value for min length/max length and validator
 *                 `SafeString` to remove base ASCII chars and escape dangerous 
 *                 characters in submitted text value. But it don't prevent 
 *                 SQL inject attacks and more.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Textarea 
extends		\MvcCore\Ext\Forms\Field 
implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
			\MvcCore\Ext\Forms\Fields\ILabel,
			\MvcCore\Ext\Forms\Fields\IMinMaxLength {

	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Label;
	use \MvcCore\Ext\Forms\Field\Props\AutoComplete;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxLength;
	use \MvcCore\Ext\Forms\Field\Props\PlaceHolder;
	use \MvcCore\Ext\Forms\Field\Props\RowsColsWrap;
	use \MvcCore\Ext\Forms\Field\Props\SpellCheck;

	/**
	 * Possible values: `textarea`.
	 * @var string
	 */
	protected $type = 'textarea';

	/**
	 * Validators: 
	 * - `SafeString` - remove from submitted value base ASCII characters from 0 to 31 incl. 
	 *                  (first column) and escape special characters: `& " ' < > | = \ %`.
	 *                  This validator is not prevent SQL inject attacks!
	 * @var \string[]|\Closure[]
	 */
	protected $validators = ['SafeString'/*, 'MinLength', 'MaxLength', 'Pattern'*/];

	/**
	 * Standard field template strings for natural rendering `control`.
	 * @var \string[]|\stdClass
	 */
	protected static $templates = [
		'control'	=> '<textarea id="{id}" name="{name}"{attrs}>{value}</textarea>',
	];
	
	/**
	 * Create new form `<textarea>` control instance.
	 * @param  array $cfg Config array with public properties and it's 
	 *                    values which you want to configure, presented 
	 *                    in camel case properties names syntax.
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function __construct (array $cfg = []) {
		parent::__construct($cfg);
		static::$templates = (object) array_merge(
			(array) parent::$templates, 
			(array) self::$templates
		);
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` after field
	 * is added into form instance by `$form->AddField();` method. Do not 
	 * use this method even if you don't develop any form field.
	 * - Check if field has any name, which is required.
	 * - Set up form and field id attribute by form id and field name.
	 * - Set up required.
	 * - Set up translate boolean property.
	 * - Set up min/max length validator if necessary.
	 * @param  \MvcCore\Ext\Form $form
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\Text
	 */
	public function SetForm (\MvcCore\Ext\IForm $form) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		parent::SetForm($form);
		$this->setFormMinMaxLength();
		return $this;
	}

	/**
	 * Return field specific data for validator.
	 * @param  array $fieldPropsDefaultValidValues 
	 * @return array
	 */
	public function & GetValidatorData ($fieldPropsDefaultValidValues = []) {
		$result = [
			'minLength'	=> $this->minLength, 
			'maxLength'	=> $this->maxLength, 
		];
		return $result;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method even if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Translate placeholder text if necessary.
	 * - Set up tab-index if necessary.
	 * @return void
	 */
	public function PreDispatch () {
		parent::PreDispatch();
		if ($this->translate && $this->placeHolder !== NULL && $this->translatePlaceholder)
			$this->placeHolder = $this->form->Translate($this->placeHolder);
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
			'minLength', 'maxLength', 
			'autoComplete',
			'placeHolder',
			'rows', 'cols', 'wrap',
			'spellCheck',
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
			'value'		=> htmlspecialchars_decode(htmlspecialchars($this->value, ENT_QUOTES), ENT_QUOTES),
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
	}
}
