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

/**
 * (c) Fabien Potencier <fabien@symfony.com>
 * Filtering URL functionality is proudly used from Symfony framework.
 * @see https://github.com/symfony/Validator
 * @see https://github.com/symfony/validator/blob/master/LICENSE
 * @see https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php
 * @author Bernhard Schussek <bschussek@gmail.com>
 * @author Fabien Potencier <fabien@symfony.com>
 */

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate URI string by PHP: 
 *				   `filter_var($rawSubmittedValue, FILTER_VALIDATE_URL);
 *				   THIS VALIDATOR DOESN'T MEAN SAFE VALUE TO PREVENT SQL INJECTS! 
 *				   To prevent sql injects - use `\PDO::prepare();` and `\PDO::execute()`.
 */
class Url extends \MvcCore\Ext\Forms\Validator
{
	const VALIDATE_DNS_TYPE_NONE	= FALSE;
	const VALIDATE_DNS_TYPE_ANY		= 'ANY';
	const VALIDATE_DNS_TYPE_A		= 'A';
	const VALIDATE_DNS_TYPE_A6		= 'A6';
	const VALIDATE_DNS_TYPE_AAAA	= 'AAAA';
	const VALIDATE_DNS_TYPE_CNAME	= 'CNAME';
	const VALIDATE_DNS_TYPE_MX		= 'MX';
	const VALIDATE_DNS_TYPE_NAPTR	= 'NAPTR';
	const VALIDATE_DNS_TYPE_NS		= 'NS';
	const VALIDATE_DNS_TYPE_PTR		= 'PTR';
	const VALIDATE_DNS_TYPE_SOA		= 'SOA';
	const VALIDATE_DNS_TYPE_SRV		= 'SRV';
	const VALIDATE_DNS_TYPE_TXT		= 'TXT';

	/**
	 * All DNS validation types.
	 * @var \string[]
	 */
	protected static $allDnsValidations = [
		self::VALIDATE_DNS_TYPE_ANY,
		self::VALIDATE_DNS_TYPE_A,
		self::VALIDATE_DNS_TYPE_A6,
		self::VALIDATE_DNS_TYPE_AAAA,
		self::VALIDATE_DNS_TYPE_CNAME,
		self::VALIDATE_DNS_TYPE_MX,
		self::VALIDATE_DNS_TYPE_NAPTR,
		self::VALIDATE_DNS_TYPE_NS,
		self::VALIDATE_DNS_TYPE_PTR,
		self::VALIDATE_DNS_TYPE_SOA,
		self::VALIDATE_DNS_TYPE_SRV,
		self::VALIDATE_DNS_TYPE_TXT,
	];

	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_URL = 0;
	const ERROR_DNS = 1;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_URL	=> "Field '{0}' requires a valid URL.",
		self::ERROR_DNS	=> "The host in field '{0}' could not be resolved.",
	];


	/**
	 * DNS optional validation.
	 * @var string|bool
	 */
	protected $dnsValidation = self::VALIDATE_DNS_TYPE_NONE;

	/**
	 * Allowed URL schemes, `http,https,ftp,ftps` allowed by default.
	 * @var \string[]
	 */
	protected $allowedSchemes = ['http', 'https', 'ftp', 'ftps',];
	

	/**
	 * Returns the allowed absolute url schemes,
	 * `http,https,ftp,ftps` allowed by default.
	 * @return \string[]
	 */
	public function GetAllowedSchemes () {
		return $this->allowedSchemes;
	}

	/**
	 * Sets the allowed absolute url schemes, 
	 * `http,https,ftp,ftps` allowed by default.
	 * @param  \string[] $allowedSchemes,...
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowedSchemes ($allowedSchemes) {
		if (!is_array($allowedSchemes)) 
			$allowedSchemes = func_get_args();	
		foreach ($allowedSchemes as $allowedScheme)
			if (!preg_match("#^[a-z]+$#", $allowedScheme)) 
				throw new \Exception(
					"[".get_class($this)."] Invalid value for URL scheme `{$allowedScheme}`."
				);
		$this->allowedSchemes = $allowedSchemes;
		return $this;
	}
	
	/**
	 * Returns the DNS validation option.
	 * @return string|bool
	 */
	public function GetDnsValidation () {
		return $this->dnsValidation;
	}

	/**
	 * Sets the DNS validation option.
	 * @param  string|bool $dnsValidation
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetDnsValidation ($dnsValidation = self::VALIDATE_DNS_TYPE_A) {
		if (!in_array($dnsValidation, self::$allDnsValidations, TRUE)) 
			throw new \Exception(
				"[".get_class($this)."] Invalid value for DNS checking `{$this->dnsValidation}`."
			);
		$this->dnsValidation = $dnsValidation;
		return $this;
	}


	/**
	 * Validate URI string by regular expression and optionally by DNS check.
	 * @see https://github.com/nette/utils/blob/72d8f087e7d750521a15e0b25b7a4f6d20ed45dc/src/Utils/Validators.php#L327
	 * @param string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$result = NULL;
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if ($rawSubmittedValue === '') return NULL;

		while (preg_match("#%[0-9a-zA-Z]{2}#", $rawSubmittedValue)) 
			$rawSubmittedValue = rawurldecode($rawSubmittedValue);
		
		$alpha = "a-z\x80-\xFF";
		$schemes = implode('|', $this->allowedSchemes);
		$pattern = <<<PATTERN
		(^
			{$schemes}://(
				(([-_0-9{$alpha}]+\\.)*											# subdomain
					[0-9{$alpha}]([-0-9{$alpha}]{0,61}[0-9{$alpha}])?\\.)?		# domain
					[{$alpha}]([-0-9{$alpha}]{0,17}[{$alpha}])?					# top domain
				|\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}						# IPv4
				|\\[[0-9a-f:]{3,39}\\]											# IPv6
			)(:\\d{1,5})?														# port
			(/\\S*)?															# path
			(\\?\\S*)?															# query
			(\\#\\S*)?															# fragment
		$)Dix
PATTERN;
		$urlIsValid = (bool) preg_match($pattern, $rawSubmittedValue);
		if (!$urlIsValid) {
			$rawSubmittedValue = NULL;
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_URL)
			);
		}

		if ($this->dnsValidation) {
			$queryPos = mb_strpos($rawSubmittedValue, '?');
			if ($queryPos !== FALSE) {
				$rawSubmittedValueWithoutQs = mb_substr($rawSubmittedValue, 0, $queryPos);
			} else {
				$rawSubmittedValueWithoutQs = $rawSubmittedValue;
			}
			$host = parse_url($rawSubmittedValueWithoutQs, PHP_URL_HOST);
			if (!is_string($host) || !checkdnsrr($host, $this->dnsValidation)) {
				$rawSubmittedValue = NULL;
				$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_DNS)
				);
			}
		}
		
		if ($rawSubmittedValue !== NULL) 
			$result = $rawSubmittedValue;

		return $result;
	}
}
