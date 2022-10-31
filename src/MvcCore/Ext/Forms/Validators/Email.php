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
 * Responsibility: Validate single email format or multiple emails formats, 
 *                 if field has defined `multiple` boolean attribute.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Email 
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IMultiple {

	use \MvcCore\Ext\Forms\Field\Props\Multiple;

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
	 * Create email validator instance.
	 * 
	 * @param  array $cfg
	 * Config array with protected properties and it's 
	 * values which you want to configure, presented 
	 * in camel case properties names syntax.
	 * 
	 * @param  bool  $multiple
	 * If control is `<input>` with `type` as `file` or `email`,
	 * this Boolean attribute indicates whether the user can enter 
	 * more than one value.
	 * If control is `<input>` with `type` as `range`, there are 
	 * rendered two connected sliders (range controls) as one control
	 * to simulate range from and range to. Result value will be array.
	 * If control is `<select>`, this Boolean attribute indicates 
	 * that multiple options can be selected in the list. When 
	 * multiple is specified, most browsers will show a scrolling 
	 * list box instead of a single line drop down.
	 * 
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function __construct(
		array $cfg = [],
		$multiple = NULL
	) {
		$errorMessages = static::$errorMessages;
		$this->consolidateCfg($cfg, func_get_args(), func_num_args());
		parent::__construct($cfg);
		if (self::$errorMessages !== $errorMessages)
			static::$errorMessages = array_replace(
				self::$errorMessages,
				$errorMessages
			);
	}

	/**
	 * Validate URI string by regular expression.
	 * @see https://github.com/nette/utils/blob/72d8f087e7d750521a15e0b25b7a4f6d20ed45dc/src/Utils/Validators.php#L308
	 * @param  string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|\string[]|NULL Safe submitted string value or array of string for `multiple` attribute defined or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if ($rawSubmittedValue === '') return NULL;
		if ($this->multiple) {
			$result = [];
			$rawValues = explode(',', $rawSubmittedValue);
		} else {
			$result = NULL;
			$rawValues = [$rawSubmittedValue];
		}
		$errorReported = FALSE;
		foreach ($rawValues as $rawValue) {
			$atom = "[-a-z0-9\!\#\$%\&'\*\+/\=\?\^_`\{\|\}~]"; // RFC 5322 unquoted characters in local-part
			$alpha = "a-z\x80-\xFF"; // superset of IDN
			$regExpPattern = <<<MAIL
			(^
				("([ !#-[\\]-~]*|\\\\[ -~])+"|{$atom}+(\\.{$atom}+)*)    # quoted or unquoted
				@
				([0-9{$alpha}]([-0-9{$alpha}]{0,61}[0-9{$alpha}])?\\.)+  # domain - RFC 1034
				[{$alpha}]([-0-9{$alpha}]{0,17}[{$alpha}])?              # top domain
			$)Dix
MAIL;
			$emailIsValid = (bool) preg_match($regExpPattern, $rawValue);
			if ($emailIsValid) {
				if ($this->multiple) {
					$result[] = $rawValue;
				} else {
					$result = $rawValue;
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
