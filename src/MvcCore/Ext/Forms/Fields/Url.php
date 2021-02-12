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
 * Responsibility: init, pre-dispatch and render `<input>` HTML element with type 
 *                 `url`. `Url` field has it's own validator to check raw submitted 
 *                 value only by `filter_var($rawSubmittedValue, FILTER_VALIDATE_URL);`.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Url extends Text {

	/**
	 * Possible values: `url`.
	 * @var string
	 */
	protected $type = 'url';

	/**
	 * Default placeholder text - `http(s)://domain.com`.
	 */
	protected $placeHolder = 'http(s)://domain.com';

	/**
	 * Validators: 
	 * - `Url` - to check URL format by PHP `filter_var($url, FILTER_VALIDATE_URL)`.
	 * @var \string[]|\Closure[]
	 */
	protected $validators = ['Url'/*, 'SafeString', 'MinLength', 'MaxLength', 'Pattern'*/];
}
