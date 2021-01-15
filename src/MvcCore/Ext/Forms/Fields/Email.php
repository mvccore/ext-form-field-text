<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, pre-dispatch and render `<input>` HTML element with 
 *				   type `email`. `Email` field has it's own validator to check 
 *				   submitted email/emails format by default.
 */
class		Email
extends		Text
implements	\MvcCore\Ext\Forms\Fields\IMultiple {

	use \MvcCore\Ext\Forms\Field\Props\Multiple;

	/**
	 * Possible values: `email`
	 * @var string
	 */
	protected $type = 'email';

	/**
	 * Default placeholder text - `your.name@domain.com`.
	 */
	protected $placeHolder = 'your.name@domain.com';

	/**
	 * Validators: 
	 * - `Email` - to check single email format or multiple emails formats, 
	 *			   if field has defined `multiple` boolean attribute.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Email'/*, 'MinLength', 'MaxLength', 'Pattern'*/];

	/**
	 * Return field specific data for validator.
	 * @param array $fieldPropsDefaultValidValues 
	 * @return array
	 */
	public function & GetValidatorData ($fieldPropsDefaultValidValues = []) {
		$parentResult = parent::GetValidatorData($fieldPropsDefaultValidValues);
		$parentResult['multiple'] = $this->multiple;
		return $parentResult;
	}
	
	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Forms\Field\Rendering` 
	 * in rendering process. Do not use this method even if you don't develop any form field.
	 * 
	 * Render control tag only without label or specific errors.
	 * @return string
	 */
	public function RenderControl () {
		if ($this->multiple) $this->SetControlAttr('multiple', 'multiple');
		return parent::RenderControl();
	}
}
