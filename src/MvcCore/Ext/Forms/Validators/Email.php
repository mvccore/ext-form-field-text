<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view 
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate single email format or multiple emails formats, 
 *				   if field has defined `multiple` boolean attribute.
 */
class		Email 
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IMultiple
{
	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_EMAIL = 0;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_EMAIL	=> "Field '{0}' requires a valid email address.",
	];

	/**
	 * Field specific values (camel case) and their validator default values.
	 * @var array
	 */
	protected static $fieldSpecificProperties = [
		'multiple'	=> NULL,
	];

	/**
	 * Validate URI string by PHP `filter_var($rawSubmittedValue, FILTER_VALIDATE_EMAIL);`.
	 * @param string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|\string[]|NULL Safe submitted string value or array of string for `multiple` attribute defined or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if ($rawSubmittedValue === '') 
			return NULL;
		if ($this->multiple) {
			$result = [];
			$rawValues = explode(',', $rawSubmittedValue);
		} else {
			$result = NULL;
			$rawValues = [$rawSubmittedValue];
		}
		$errorReported = FALSE;
		foreach ($rawValues as $rawValue) {
			$safeValue = filter_var($rawValue, FILTER_VALIDATE_EMAIL);
			if ($safeValue !== FALSE) {
				if ($this->multiple) {
					$result[] = $safeValue;
				} else {
					$result = $safeValue;
				}
			} else {
				if (!$errorReported) {
					$errorReported = TRUE;
					$this->field->AddValidationError(
						static::GetErrorMessage(self::ERROR_EMAIL)
					);
				}
			}
		}
		return $result;
	}
}
