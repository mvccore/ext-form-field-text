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
 * Responsibility: Validate raw user password by configured password strength
 *                 rules.  Password still could contain very dangerous
 *                 characters for XSS, SQL or any other attacks. Be careful!!!
 *                 This doesn't escape everything. It only check if configured
 *                 character groups are presented and how much and that's all.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Password extends \MvcCore\Ext\Forms\Validator {

	/**
	 * Default minimum password characters length - 12.
	 */
	const MIN_LENGTH = 12;

	/**
	 * Default maximum password characters length - 255.
	 */
	const MAX_LENGTH = 255;

	/**
	 * Default special characters collection - !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 */
	const SPECIAL_CHARS = '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~';

	/**
	 * Default lower case chars count presented in password - 1;
	 */
	const MIN_LOWERCASE_CHARS_COUNT = 1;

	/**
	 * Default upper case chars count presented in password - 1;
	 */
	const MIN_UPPERCASE_CHARS_COUNT = 1;

	/**
	 * Default digit chars count presented in password - 1;
	 */
	const MIN_DIGIT_CHARS_COUNT = 1;

	/**
	 * Default special chars count presented in password - 1;
	 */
	const MIN_SPECIAL_CHARS_COUNT = 1;

	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_MIN_LENGTH			= 0;
	const ERROR_MAX_LENGTH			= 1;
	const ERROR_LOWERCASE_CHARS		= 2;
	const ERROR_LOWERCASE_CHARS_MIN	= 3;
	const ERROR_UPPERCASE_CHARS		= 4;
	const ERROR_UPPERCASE_CHARS_MIN	= 5;
	const ERROR_DIGIT_CHARS			= 6;
	const ERROR_DIGIT_CHARS_MIN		= 7;
	const ERROR_SPECIAL_CHARS		= 8;
	const ERROR_SPECIAL_CHARS_MIN	= 9;

	/**
	 * Global minimum password characters length, default value is 12.
	 * @var int
	 */
	protected $mustHaveMinLength = self::MIN_LENGTH;

	/**
	 * Global maximum password characters length, default value is 255.
	 * @var int
	 */
	protected $mustHaveMaxLength = self::MAX_LENGTH;

	/**
	 * Password strength rule to have any lower case character presented in password.
	 * Default value is `TRUE` to must have lower case character in password.
	 * Lower case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 * @var bool
	 */
	protected $mustHaveLowerCaseChars = TRUE;

	/**
	 * Password strength rule to have minimum lower case characters count presented in password.
	 * Default value is `1` to must have at least one lower case character in password.
	 * Lower case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 * @var int
	 */
	protected $mustHaveLowerCaseCharsCount = self::MIN_LOWERCASE_CHARS_COUNT;

	/**
	 * Password strength rule to have any upper case character presented in password.
	 * Default value is `TRUE` to must have upper case character in password.
	 * Upper case characters from latin alphabet: ABCDEFGHIJKLMNOPQRSTUVWXYZ.
	 * @var bool
	 */
	protected $mustHaveUpperCaseChars = TRUE;

	/**
	 * Password strength rule to have minimum upper case characters count presented in password.
	 * Default value is `1` to must have at least one upper case character in password.
	 * Upper case characters from latin alphabet: ABCDEFGHIJKLMNOPQRSTUVWXYZ.
	 * @var int
	 */
	protected $mustHaveUpperCaseCharsCount = self::MIN_UPPERCASE_CHARS_COUNT;

	/**
	 * Password strength rule to have any digit presented in password.
	 * Default value is `TRUE` to must have digit characters in password.
	 * Digit (arabian) characters from arabian alphabet: 0123456789.
	 * @var bool
	 */
	protected $mustHaveDigits = TRUE;

	/**
	 * Password strength rule to have minimum digits count presented in password.
	 * Default value is `1` to must have at least one digit character in password.
	 * Digit (arabian) characters from arabian alphabet: 0123456789.
	 * @var int
	 */
	protected $mustHaveDigitsCount = self::MIN_DIGIT_CHARS_COUNT;

	/**
	 * Password strength rule to have any special character presented in password.
	 * Default value is `TRUE` to must have special character in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 *
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @var bool
	 */
	protected $mustHaveSpecialChars = TRUE;

	/**
	 * Password strength rule to have minimum special characters count presented in password.
	 * Default value is `1` to must have at least one special character in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 *
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @var int
	 */
	protected $mustHaveSpecialCharsCount = self::MIN_SPECIAL_CHARS_COUNT;

	/**
	 * Special characters collection to check if any of them is presented in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 *
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @var string
	 */
	protected $specialChars = self::SPECIAL_CHARS;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_MIN_LENGTH			=> "Password must have a minimum length of {1} characters.",
		self::ERROR_MAX_LENGTH			=> "Password must have a maximum length of {1} characters.",
		self::ERROR_LOWERCASE_CHARS		=> "Password must contain lower case characters ({1}).",
		self::ERROR_LOWERCASE_CHARS_MIN	=> "Password must contain at minimum {1} lower case characters ({2}).",
		self::ERROR_UPPERCASE_CHARS		=> "Password must contain upper case characters ({1}).",
		self::ERROR_UPPERCASE_CHARS_MIN	=> "Password must contain at minimum {1} upper case characters ({2}).",
		self::ERROR_DIGIT_CHARS			=> "Password must contain digits ({1}).",
		self::ERROR_DIGIT_CHARS_MIN		=> "Password must contain at minimum {1} digits ({2}).",
		self::ERROR_SPECIAL_CHARS		=> "Password must contain special characters ( {1} ).",
		self::ERROR_SPECIAL_CHARS_MIN	=> "Password must contain at minimum {1} special characters ( {2} ).",
	];

	/**
	 * Get global minimum password characters length, default value is 12.
	 * @return int
	 */
	public function GetMustHaveMinLength () {
		return $this->mustHaveMinLength;
	}

	/**
	 * Set global minimum password characters length, default value is 12.
	 * @param  int $mustHaveMinLength
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetMustHaveMinLength ($mustHaveMinLength = self::MIN_LENGTH) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->mustHaveMinLength = $mustHaveMinLength;
		return $this;
	}

	/**
	 * Get global maximum password characters length, default value is 255.
	 * @return int
	 */
	public function GetMustHaveMaxLength () {
		return $this->mustHaveMaxLength;
	}

	/**
	 * Set global maximum password characters length, default value is 255.
	 * @param  int $mustHaveMaxLength
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetMustHaveMaxLength ($mustHaveMaxLength = self::MAX_LENGTH) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->mustHaveMaxLength = $mustHaveMaxLength;
		return $this;
	}

	/**
	 * Get password strength rule to have any lower case character presented in password.
	 * Default value is `TRUE` to must have lower case character in password.
	 * Lower case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 *
	 * This function returns array with the rule `boolean` as first item and
	 * second item is minimum lower case characters count i n password as `integer`.
	 * If you set function first argument to `FALSE`, function returns only array
	 * `[TRUE]`, if the rule is `TRUE` or an empty array `[]` if the rule is `FALSE`.
	 * @param  bool $getWithMinCount
	 * @return array
	 */
	public function GetMustHaveLowerCaseChars ($getWithMinCount = TRUE) {
		if ($getWithMinCount)
			return [$this->mustHaveLowerCaseChars, $this->mustHaveLowerCaseCharsCount];
		return $this->mustHaveLowerCaseChars ? [TRUE] : [];
	}

	/**
	 * Set password strength rule to have any lower case character presented in password.
	 * Default value is `TRUE` to must have lower case character in password.
	 * Lower case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 *
	 * Function has second argument to set minimum lower case characters in password.
	 * Default value is at least one lower case character in password.
	 * @param  bool $mustHaveLowerCaseChars
	 * @param  int  $minCount
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetMustHaveLowerCaseChars ($mustHaveLowerCaseChars = TRUE, $minCount = self::MIN_LOWERCASE_CHARS_COUNT) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->mustHaveLowerCaseChars = $mustHaveLowerCaseChars;
		$this->mustHaveLowerCaseCharsCount = $minCount;
		return $this;
	}

	/**
	 * Get password strength rule to have any upper case character presented in password.
	 * Default value is `TRUE` to must have upper case character in password.
	 * Upper case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 *
	 * This function returns array with the rule `boolean` as first item and
	 * second item is minimum upper case characters count i n password as `integer`.
	 * If you set function first argument to `FALSE`, function returns only array
	 * `[TRUE]`, if the rule is `TRUE` or an empty array `[]` if the rule is `FALSE`.
	 * @param  bool $getWithMinCount
	 * @return array
	 */
	public function GetMustHaveUpperCaseChars ($getWithMinCount = TRUE) {
		if ($getWithMinCount)
			return [$this->mustHaveUpperCaseChars, $this->mustHaveUpperCaseCharsCount];
		return $this->mustHaveUpperCaseChars ? [TRUE] : [];
	}

	/**
	 * Set password strength rule to have any upper case character presented in password.
	 * Default value is `TRUE` to must have upper case character in password.
	 * Upper case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 *
	 * Function has second argument to set minimum upper case characters in password.
	 * Default value is at least one upper case character in password.
	 * @param  bool $mustHaveUpperCaseChars
	 * @param  int  $minCount
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetMustHaveUpperCaseChars ($mustHaveUpperCaseChars = TRUE, $minCount = self::MIN_UPPERCASE_CHARS_COUNT) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->mustHaveUpperCaseChars = $mustHaveUpperCaseChars;
		$this->mustHaveUpperCaseCharsCount = $minCount;
		return $this;
	}

	/**
	 * Get password strength rule to have any digit presented in password.
	 * Default value is `TRUE` to must have digit characters in password.
	 * Digit (arabian) characters from arabian alphabet: 0123456789.
	 *
	 * This function returns array with the rule `boolean` as first item and
	 * second item is minimum digit characters count i n password as `integer`.
	 * If you set function first argument to `FALSE`, function returns only array
	 * `[TRUE]`, if the rule is `TRUE` or an empty array `[]` if the rule is `FALSE`.
	 * @param  bool $getWithMinCount
	 * @return array|bool
	 */
	public function GetMustHaveDigits ($getWithMinCount = TRUE) {
		if ($getWithMinCount)
			return [$this->mustHaveDigits, $this->mustHaveDigitsCount];
		return $this->mustHaveDigits ? [TRUE] : [];
	}

	/**
	 * Set password strength rule to have any digit presented in password.
	 * Default value is `TRUE` to must have digit characters in password.
	 * Digit (arabian) characters from arabian alphabet: 0123456789.
	 *
	 * Function has second argument to set minimum digit characters in password.
	 * Default value is at least one digit character in password.
	 * @param  bool $mustHaveDigits
	 * @param  int  $minCount
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetMustHaveDigits ($mustHaveDigits = TRUE, $minCount = self::MIN_DIGIT_CHARS_COUNT) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->mustHaveDigits = $mustHaveDigits;
		$this->mustHaveDigitsCount = $minCount;
		return $this;
	}

	/**
	 * Get password strength rule to have any special character presented in password.
	 * Default value is `TRUE` to must have special character in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 *
	 * This function returns array with the rule `boolean` as first item and
	 * second item is minimum special characters count i n password as `integer`.
	 * If you set function first argument to `FALSE`, function returns only array
	 * `[TRUE]`, if the rule is `TRUE` or an empty array `[]` if the rule is `FALSE`.
	 *
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @param  bool $getWithMinCount
	 * @return array|bool
	 */
	public function GetMustHaveSpecialChars ($getWithMinCount = TRUE) {
		if ($getWithMinCount)
			return [$this->mustHaveSpecialChars, $this->mustHaveSpecialCharsCount];
		return $this->mustHaveSpecialChars;
	}

	/**
	 * Set password strength rule to have any special character presented in password.
	 * Default value is `TRUE` to must have special character in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 *
	 * Function has second argument to set minimum special characters in password.
	 * Default value is at least one digit character in password.
	 *
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @param  bool $mustHaveSpecialChars
	 * @param  int  $minCount
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetMustHaveSpecialChars ($mustHaveSpecialChars = TRUE, $minCount = self::MIN_SPECIAL_CHARS_COUNT) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->mustHaveSpecialChars = $mustHaveSpecialChars;
		$this->mustHaveSpecialCharsCount = $minCount;
		return $this;
	}

	/**
	 * Get special characters collection to check if any of them is presented in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 * @return string
	 */
	public function GetSpecialChars	() {
		return $this->specialChars;
	}

	/**
	 * Set special characters collection to check if any of them is presented in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 *
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by this method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @param  string $specialChars
	 * @return \MvcCore\Ext\Forms\Validators\Password
	 */
	public function SetSpecialChars ($specialChars = self::SPECIAL_CHARS) {
		/** @var \MvcCore\Ext\Forms\Validator $this */
		$this->specialChars = $specialChars;
		return $this;
	}

	/**
	 * Create new password strength rules validator instance.
	 * This validator accepts first argument to be an array with camel case
	 * keyed records representing protected properties (password strength rules)
	 * you need to configure.
	 * Example:
	 * ```
	 *   $validator = new \MvcCore\Ext\Forms\Validators\Password([
	 *       'mustHaveMinLength'           => 12,
	 *       'mustHaveMaxLength'           => 255,
	 *       'mustHaveLowerCaseChars'      => TRUE,
	 *       'mustHaveLowerCaseCharsCount' => 1,
	 *       'mustHaveUpperCaseChars'      => TRUE,
	 *       'mustHaveUpperCaseCharsCount' => 1,
	 *       'mustHaveDigits'              => TRUE,
	 *       'mustHaveDigitsCount'         => 1,
	 *       'mustHaveSpecialChars'        => TRUE,
	 *       'mustHaveSpecialCharsCount'   => 1,
	 *       'specialChars'                => '!"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~',
	 *   );
	 * ```
	 * 
	 * @param  array  $cfg
	 * Config array with protected properties and it's 
	 * values which you want to configure, presented 
	 * in camel case properties names syntax.
	 * 
	 * @param  int    $mustHaveMinLength
	 * Global minimum password characters length, default value is 12.
	 * @param  int    $mustHaveMaxLength
	 * Global maximum password characters length, default value is 255.
	 * @param  bool   $mustHaveLowerCaseChars
	 * Password strength rule to have any lower case character presented in password.
	 * Default value is `TRUE` to must have lower case character in password.
	 * Lower case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 * @param  int    $mustHaveLowerCaseCharsCount
	 * Password strength rule to have minimum lower case characters count presented in password.
	 * Default value is `1` to must have at least one lower case character in password.
	 * Lower case characters from latin alphabet: abcdefghijklmnopqrstuvwxyz.
	 * @param  bool   $mustHaveUpperCaseChars
	 * Password strength rule to have any upper case character presented in password.
	 * Default value is `TRUE` to must have upper case character in password.
	 * Upper case characters from latin alphabet: ABCDEFGHIJKLMNOPQRSTUVWXYZ.
	 * @param  int    $mustHaveUpperCaseCharsCount
	 * Password strength rule to have minimum upper case characters count presented in password.
	 * Default value is `1` to must have at least one upper case character in password.
	 * Upper case characters from latin alphabet: ABCDEFGHIJKLMNOPQRSTUVWXYZ.
	 * @param  bool   $mustHaveDigits
	 * Password strength rule to have any digit presented in password.
	 * Default value is `TRUE` to must have digit characters in password.
	 * Digit (arabian) characters from arabian alphabet: 0123456789.
	 * @param  int    $mustHaveDigitsCount
	 * Password strength rule to have minimum digits count presented in password.
	 * Default value is `1` to must have at least one digit character in password.
	 * Digit (arabian) characters from arabian alphabet: 0123456789.
	 * @param  bool   $mustHaveSpecialChars
	 * Password strength rule to have any special character presented in password.
	 * Default value is `TRUE` to must have special character in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @param  int    $mustHaveSpecialCharsCount
	 * Password strength rule to have minimum special characters count presented in password.
	 * Default value is `1` to must have at least one special character in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * @param  string $specialChars
	 * Special characters collection to check if any of them is presented in password.
	 * Default special characters are: !"#$%&\'()*+,-./:;<=>?@[\\]^_`{|}~.
	 * It's possible to configure own collection of special characters to check
	 * if any of them is presented in password by method:
	 * `$validator->SetSpecialChars('...');` or by constructor configuration record:
	 * `new \MvcCore\Ext\Forms\Validators\Password(['specialChars' => '...']);
	 * 
	 * @return void
	 */
	public function __construct (
		array $cfg = [],
		$mustHaveMinLength = NULL,
		$mustHaveMaxLength = NULL,
		$mustHaveLowerCaseChars = NULL,
		$mustHaveLowerCaseCharsCount = NULL,
		$mustHaveUpperCaseChars = NULL,
		$mustHaveUpperCaseCharsCount = NULL,
		$mustHaveDigits = NULL,
		$mustHaveDigitsCount = NULL,
		$mustHaveSpecialChars = NULL,
		$mustHaveSpecialCharsCount = NULL,
		$specialChars = NULL
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
	 * Validate raw user password by configured rules. Password still could contain
	 * very dangerous characters for XSS, SQL or any other attacks. Be careful!!!
	 * @param  string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|NULL  Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$password = trim((string) $rawSubmittedValue);
		$passwordLength = mb_strlen($password);
		if ($passwordLength === 0) return NULL;

		// check password global minimum and maximum length:
		if ($passwordLength < $this->mustHaveMinLength)
			$this->field->AddValidationError(
				static::GetErrorMessage(static::ERROR_MIN_LENGTH),
				[$this->mustHaveMinLength]
			);
		if ($passwordLength > $this->mustHaveMaxLength) {
			$password = mb_substr($password, 0, $this->mustHaveMaxLength);
			$this->field->AddValidationError(
				static::GetErrorMessage(static::ERROR_MAX_LENGTH),
				[$this->mustHaveMaxLength]
			);
		}

		// check password lower case characters and minimum lower case characters count if necessary:
		if ($this->mustHaveLowerCaseChars) {
			$lowerCaseChars = preg_replace('#[^a-z]#', '', $password);
			$lowerCaseCharsCount = strlen($lowerCaseChars);
			if ($this->mustHaveLowerCaseCharsCount > 1 && $lowerCaseCharsCount < $this->mustHaveLowerCaseCharsCount) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_LOWERCASE_CHARS_MIN),
					[$this->mustHaveLowerCaseCharsCount, '[a-z]']
				);
			} else if ($lowerCaseCharsCount === 0) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_LOWERCASE_CHARS),
					['[a-z]']
				);
			}
		}

		// check password upper case characters and minimum upper case characters count if necessary:
		if ($this->mustHaveUpperCaseChars) {
			$upperCaseChars = preg_replace('#[^A-Z]#', '', $password);
			$upperCaseCharsCount = strlen($upperCaseChars);
			if ($this->mustHaveUpperCaseCharsCount > 1 && $upperCaseCharsCount < $this->mustHaveUpperCaseCharsCount) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_UPPERCASE_CHARS_MIN),
					[$this->mustHaveUpperCaseCharsCount, '[A-Z]']
				);
			} else if ($upperCaseCharsCount === 0) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_UPPERCASE_CHARS),
					['[A-Z]']
				);
			}
		}

		// check password digit characters and minimum digit characters count if necessary:
		if ($this->mustHaveDigits) {
			$digitChars = preg_replace('#[^0-9]#', '', $password);
			$digitCharsCount = strlen($digitChars);
			if ($this->mustHaveDigitsCount > 1 && $digitCharsCount < $this->mustHaveDigitsCount) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_DIGIT_CHARS_MIN),
					[$this->mustHaveDigitsCount, '[0-9]']
				);
			} else if ($digitCharsCount === 0) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_DIGIT_CHARS),
					['[0-9]']
				);
			}
		}

		// check password special characters and minimum special characters count if necessary:
		if ($this->mustHaveSpecialChars) {
			$specialCharsArr = str_split($this->specialChars);
			$passwordCharsArr = str_split($password);
			$specialChars = array_intersect($passwordCharsArr, $specialCharsArr);
			$specialCharsCount = count($specialChars);
			if ($this->mustHaveSpecialCharsCount > 1 && $specialCharsCount < $this->mustHaveSpecialCharsCount) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_SPECIAL_CHARS_MIN),
					[$this->mustHaveSpecialCharsCount, htmlspecialchars($this->specialChars, ENT_QUOTES)]
				);
			} else if ($specialCharsCount === 0) {
				$this->field->AddValidationError(
					static::GetErrorMessage(static::ERROR_SPECIAL_CHARS),
					[htmlspecialchars($this->specialChars, ENT_QUOTES)]
				);
			}
		}

		return $password;
	}
}
