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

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate submitted value by regular expression from 
 *				   configured `pattern` HTML attribute.
 */
class		Pattern 
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IPattern {

	use \MvcCore\Ext\Forms\Field\Props\Pattern;
	
	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_INVALID_FORMAT = 0;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_INVALID_FORMAT	=> "Field '{0}' has invalid format ('{1}').",
	];

	/**
	 * Field specific values (camel case) and their validator default values.
	 * @var array
	 */
	protected static $fieldSpecificProperties = [
		'pattern'	=> NULL,
	];

	/**
	 * Validate raw user input by configured regular expression match pattern.
  * @param string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		$resultLength = mb_strlen($rawSubmittedValue);
		if ($resultLength === 0) return NULL;
		$result = NULL;
		if ($this->pattern !== NULL) {

			$pattern = $this->pattern;
			$beginBorderChar = mb_strpos($pattern, '#') === 0;
			$endBorderChar = mb_substr($pattern, mb_strlen($pattern) - 2, 1) === '#';
			if (!$beginBorderChar && !$endBorderChar)
				$pattern = '#' . $pattern . '#';
			$matched = @preg_match($pattern, $rawSubmittedValue, $matches);
			if ($matched) 
				$result = $rawSubmittedValue;
		} else {
			$result = $rawSubmittedValue;
		}
		if ($result === NULL) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_INVALID_FORMAT),
				[$this->pattern]
			);
		}
		return $result;
	}
}
