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

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate minimum or maximum characters length in submitted
 *				   value by configured field `minlength` and `maxlength` HTML
 *				   attributes. To measure characters length in submitted value,
 *				   validator uses multi-byte string function `mb_strlen()`.
 */
class		MinMaxLength
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IMinMaxLength {

	use \MvcCore\Ext\Forms\Field\Props\MinMaxLength;

	/**
	 * Valid email address error message index.
	 * @var int
	 */
	const ERROR_MIN_LENGTH = 0;
	const ERROR_MAX_LENGTH = 1;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_MIN_LENGTH	=> "Field '{0}' requires at least {1} characters.",
		self::ERROR_MAX_LENGTH	=> "Field '{0}' requires no more than {1} characters.",
	];

	/**
	 * Field specific values (camel case) and their validator default values.
	 * @var array
	 */
	protected static $fieldSpecificProperties = [
		'minLength'	=> NULL,
		'maxLength'	=> NULL,
	];

	/**
	 * Validate raw user input with maximum string length check.
	 * @param string|array $submitValue Raw submitted value from user.
	 * @return string|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		$resultLength = mb_strlen($rawSubmittedValue);
		if ($resultLength === 0) return NULL;
		$result = $rawSubmittedValue;
		if (
			$this->minLength !== NULL &&
			$this->minLength > 0 &&
			$resultLength < $this->minLength
		) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_MIN_LENGTH), [$this->minLength]
			);
		}
		if (
			$this->maxLength !== NULL &&
			$this->maxLength > 0 &&
			$resultLength > $this->maxLength
		) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_MAX_LENGTH), [$this->maxLength]
			);
		}
		return $result;
	}
}
