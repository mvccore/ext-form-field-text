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
 * Responsibility: init, pre-dispatch and render `<input>` HTML element 
 *				   with type `password`. `Password` could have a `Password`
 *				   validator define (not defined by default) to check 
 *				   submitted value for password strength rules. 
 *				   But raw user password still could contain very dangerous 
 *				   characters for XSS, SQL or any other attacks. Be careful!!! 
 *				   It doesn't escape everything. It only check if configured 
 *				   character groups are presented and how much and that's all.
 */
class Password extends Text {

	/**
	 * Possible values: `password`.
	 * @var string
	 */
	protected $type = 'password';

	/**
	 * Validators: 
	 * - `Password` - Validate raw user password by configured password strength rules.
	 * 				  Password still could contain very dangerous characters for XSS, 
	 * 				  SQL or any other attacks. Be careful!!! This doesn't escape everything.
	 * 				  It only check if configured character groups are presented and how much
	 * 				  and that's all.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Password'/*, 'SafeString', 'MinLength', 'MaxLength', 'Pattern'*/];
}
