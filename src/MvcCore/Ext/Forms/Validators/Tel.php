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
 * Responsibility: Validate phone number only by removing all other characters 
 *                 than digits and plus. To validate phone number really deeply
 *                 by local conventions, use Laminas validator instead.
 * @see https://github.com/laminas/laminas-i18n
 * @see https://github.com/laminas/laminas-i18n/blob/2.12.x/src/Validator/PhoneNumber.php
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Tel extends \MvcCore\Ext\Forms\Validator {

	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_PHONE = 0;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_PHONE	=> "Field '{0}' requires a valid phone number.",
	];

	/**
	 * Validate phone number only by removing all other characters than digits and plus.
	 * To validate phone number really deeply - use Laminas validator instead:
	 * @see https://github.com/laminas/laminas-i18n
	 * @see https://github.com/laminas/laminas-i18n/blob/2.12.x/src/Validator/PhoneNumber.php
	 * @param  string|array      $rawSubmittedValue
	 * @return string|array|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		// remove spaces
		$rawSubmittedValue = str_replace(' ', '', (string) $rawSubmittedValue);
		// eliminate every char except 0-9 and +
		$result = preg_replace("#[^0-9\+]#", '', $rawSubmittedValue);
		$resultLength = mb_strlen($result);
		if ($resultLength === 0) return NULL;
		// add error if result is an empty string or if there was any other characters than numbers and plus.
		if ($resultLength && $resultLength !== mb_strlen($rawSubmittedValue)) 
			$this->field->AddValidationError(
				 static::GetErrorMessage(self::ERROR_PHONE)	
			);
		return $result;
	}
}
