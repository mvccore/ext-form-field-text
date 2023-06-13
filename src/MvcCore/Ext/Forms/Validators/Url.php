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

/**
 * (c) Fabien Potencier <fabien@symfony.com>
 * Filtering URL functionality is proudly used from Symfony framework with slight improvements.
 * @see https://github.com/symfony/Validator/blob/master/Constraints/UrlValidator.php
 */

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate URI string by PHP regular expression and optionally by DNS record.
 *                 THIS VALIDATOR DOESN'T MEAN YOU WILL GET SAFE VALUE TO PREVENT SQL INJECTS! 
 *                 To prevent sql injects - use `\PDO::prepare();` and `\PDO::execute()`.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Url extends \MvcCore\Ext\Forms\Validator {

	/**
	 * @author Bernhard Schussek <bschussek@gmail.com>
	 * @see https://en.wikipedia.org/wiki/Uniform_Resource_Identifier
	 */
	const PATTERN_ALL				= '~^{%protocol}{%auth}{%hostname}{%port}{%path}{%query}{%fragment}$~iu';

	const PATTERN_PART_AUTH			= '(((?:[\_\.\pL\pN-]|%[0-9A-Fa-f]{2})+:)?((?:[\_\.\pL\pN-]|%[0-9A-Fa-f]{2})+)@)?';

	const PATTERN_PART_DOMAIN		= '([\pL\pN\pS\-\_\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?)';

	const PATTERN_PART_IPV4			= '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';

	const PATTERN_PART_IPV6			= '\[(?:(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9A-Fa-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})))?::(?:(?:(?:[0-9A-Fa-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){0,1}(?:(?:[0-9A-Fa-f]{1,4})))?::(?:(?:(?:[0-9A-Fa-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){0,2}(?:(?:[0-9A-Fa-f]{1,4})))?::(?:(?:(?:[0-9A-Fa-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){0,3}(?:(?:[0-9A-Fa-f]{1,4})))?::(?:(?:[0-9A-Fa-f]{1,4})):)(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){0,4}(?:(?:[0-9A-Fa-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){0,5}(?:(?:[0-9A-Fa-f]{1,4})))?::)(?:(?:[0-9A-Fa-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9A-Fa-f]{1,4})):){0,6}(?:(?:[0-9A-Fa-f]{1,4})))?::))))\]';
	
	const PATTERN_PART_PORT			= '(:[0-9]+)?';

	const PATTERN_PART_PATH			= '(?:/(?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%[0-9A-Fa-f]{2})*)*';

	const PATTERN_PART_QUERY		= '(?:\?(?:[\pL\pN\-._\~!$&\'\[\]()*+,;=:@/?]|%[0-9A-Fa-f]{2})*)?';

	const PATTERN_PART_FRAGMENT		= '(?:\#(?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%[0-9A-Fa-f]{2})*)?';


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


	const ALLOW_PROTOCOL_NONE		= 0;

	const ALLOW_PROTOCOL_RELATIVE	= 1;

	const ALLOW_PROTOCOL_ABSOLUTE	= 2;

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
	 * DNS validation. No DNS validation by default.
	 * @var string|bool
	 */
	protected $dnsValidation = self::VALIDATE_DNS_TYPE_NONE;

	/**
	 * Allow url with or without defined protocol
	 * - no protocol: `domain.com/path`,
	 * - relative protocol: `//domain.com/path`,
	 * - absolute protocol: `https://domain.com/path` (by default).
	 * @var int
	 */
	protected $allowProtocol = self::ALLOW_PROTOCOL_ABSOLUTE;

	/**
	 * Allowed URL schemes. 
	 * Schemes `http,https,ftp,ftps` are allowed by default.
	 * @var \string[]
	 */
	protected $allowedSchemes = ['http', 'https', 'ftp', 'ftps',];
	
	/**
	 * Allow url with basic authentication before hostname, 
	 * like: `https://john.doe@www.domain.com/...`.
	 * `FALSE` by default.
	 * @var bool
	 */
	protected $allowBasicAuth = FALSE;
	
	/**
	 * Allow url with domains, `TRUE` by default.
	 * @var bool
	 */
	protected $allowDomains = TRUE;
	
	/**
	 * Allow url with IPv4, `TRUE` by default.
	 * @var bool
	 */
	protected $allowIPv4 = TRUE;
	
	/**
	 * Allow url with IPv4, `TRUE` by default.
	 * @var bool
	 */
	protected $allowIPv6 = TRUE;
	
	/**
	 * Allow url with ports, `TRUE` by default.
	 * @var bool
	 */
	protected $allowPorts = TRUE;

	/**
	 * Set allowed domains or IPs. 
	 * All domains or ips are allowed by default.
	 * @var \string[]
	 */
	protected $allowedHostnames = [];

	/**
	 * Allowed port numbers. 
	 * No port number or any port number is allowed by default.
	 * @var \string[]
	 */
	protected $allowedPorts = [];

	/**
	 * Pattern for URL validation.
	 * @var string|NULL
	 */
	protected $pattern = NULL;

	/**
	 * Completed back reference index for completed pattern 
	 * to check hostname against allowed values.
	 * @var int|NULL
	 */
	protected $backReferencePosHostname = NULL;

	/**
	 * Completed back reference index for completed pattern 
	 * to check port against allowed values.
	 * @var int|NULL
	 */
	protected $backReferencePosPort = NULL;
	

	/**
	 * Create URL validator instance.
	 * 
	 * @param  array     $cfg
	 * Config array with protected properties and it's 
	 * values which you want to configure, presented 
	 * in camel case properties names syntax.
	 * 
	 * @param  string    $dnsValidation
	 * DNS validation. No DNS validation by default.
	 * @param  int       $allowProtocol
	 * Allow url with or without defined protocol
	 * - no protocol: `domain.com/path`,
	 * - relative protocol: `//domain.com/path`,
	 * - absolute protocol: `https://domain.com/path` (by default).
	 * @param  \string[] $allowedSchemes
	 * Allowed URL schemes. 
	 * Schemes `http,https,ftp,ftps` are allowed by default.
	 * @param  bool      $allowBasicAuth
	 * Allow url with basic authentication before hostname, 
	 * like: `https://john.doe@www.domain.com/...`.
	 * `FALSE` by default.
	 * @param  bool      $allowDomains
	 * Allow url with domains, `TRUE` by default.
	 * @param  bool      $allowIPv4
	 * Allow url with IPv4, `TRUE` by default.
	 * @param  bool      $allowIPv6
	 * Allow url with IPv4, `TRUE` by default.
	 * @param  bool      $allowPorts
	 * Allow url with ports, `TRUE` by default.
	 * @param  \string[] $allowedHostnames
	 * Set allowed domains or IPs. 
	 * All domains or ips are allowed by default.
	 * @param  \string[] $allowedPorts
	 * Allowed port numbers. 
	 * No port number or any port number is allowed by default.
	 * @param  string    $pattern
	 * Pattern for validation
	 * @param  int       $backReferencePosHostname
	 * Back reference index for completed pattern 
	 * to check hostname against allowed values.
	 * @param  int       $backReferencePosPort
	 * Back reference index for completed pattern 
	 * to check port against allowed values.
	 * 
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function __construct (
		array $cfg = [],
		$dnsValidation = NULL,
		$allowProtocol = NULL,
		array $allowedSchemes = [],
		$allowBasicAuth = NULL,
		$allowDomains = NULL,
		$allowIPv4 = NULL,
		$allowIPv6 = NULL,
		$allowPorts = NULL,
		array $allowedHostnames = [],
		array $allowedPorts = [],
		$pattern = NULL,
		$backReferencePosHostname = NULL,
		$backReferencePosPort = NULL
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
	 * Get allowed url with or without defined protocol
	 * - no protocol: `domain.com/path`,
	 * - relative protocol: `//domain.com/path`,
	 * - absolute protocol: `https://domain.com/path` (by default).
	 * @return int
	 */
	public function GetAllowProtocol () {
		return $this->allowProtocol;
	}

	/**
	 * Set allowed url with or without defined protocol
	 * - no protocol: `domain.com/path`,
	 * - relative protocol: `//domain.com/path`,
	 * - absolute protocol: `https://domain.com/path` (by default).
	 * @param  int $allowProtocol
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowProtocol ($allowProtocol = self::ALLOW_PROTOCOL_ABSOLUTE) {
		$this->allowProtocol = $allowProtocol;
		return $this;
	}
	
	/**
	 * Get if allowed url with basic authentication before hostname, 
	 * like: `https://john.doe@www.domain.com/...`.
	 * No basic authentication allowed by default.
	 * @return bool
	 */
	public function GetAllowBasicAuth () {
		return $this->allowBasicAuth;
	}

	/**
	 * Set if allowed url with basic authentication before hostname, 
	 * like: `https://john.doe@www.domain.com/...`.
	 * No basic authentication allowed by default.
	 * @param  bool $allowBasicAuth
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowBasicAuth ($allowBasicAuth = TRUE) {
		$this->allowBasicAuth = $allowBasicAuth;
		return $this;
	}
	
	/**
	 * Get if allowed url with domains, `TRUE` by default.
	 * @return bool
	 */
	public function GetAllowDomains () {
		return $this->allowDomains;
	}

	/**
	 * Set if allowed url with domains, `TRUE` by default.
	 * @param  bool $allowDomains
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowDomains ($allowDomains = TRUE) {
		$this->allowDomains = $allowDomains;
		return $this;
	}
	
	/**
	 * Get if allowed url with IPv4, `TRUE` by default.
	 * @return bool
	 */
	public function GetAllowIPv4 () {
		return $this->allowIPv4;
	}

	/**
	 * Set if allowed url with IPv4, `TRUE` by default.
	 * @param  bool $allowIPv4
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowIPv4 ($allowIPv4 = TRUE) {
		$this->allowIPv4 = $allowIPv4;
		return $this;
	}
	
	/**
	 * Get if allowed url with IPv6, `TRUE` by default.
	 * @return bool
	 */
	public function GetAllowIPv6 () {
		return $this->allowIPv6;
	}

	/**
	 * Set if allowed url with IPv6, `TRUE` by default.
	 * @param  bool $allowIPv6
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowIPv6 ($allowIPv6 = TRUE) {
		$this->allowIPv6 = $allowIPv6;
		return $this;
	}
	
	/**
	 * Get if allowed url with ports, `TRUE` by default.
	 * @return bool
	 */
	public function GetAllowPorts () {
		return $this->allowPorts;
	}

	/**
	 * Set if allowed url with ports, `TRUE` by default.
	 * @param  bool $allowPorts
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowPorts ($allowPorts = TRUE) {
		$this->allowPorts = $allowPorts;
		return $this;
	}

	/**
	 * Set allowed domains or IPs. 
	 * All domains or ips are allowed by default.
	 * This overwrites configuration by methods:
	 * - `SetAllowDomains()`
	 * - `SetAllowIPv4()`
	 * - `SetAllowIPv6()`
	 * There will be allowed only defined values.
	 * @return \string[]
	 */
	public function GetAllowedHostnames () {
		return $this->allowedHostnames;
	}

	/**
	 * Set allowed domains or IPs. 
	 * All domains or ips are allowed by default.
	 * This overwrites configuration by methods:
	 * - `SetAllowDomains()`
	 * - `SetAllowIPv4()`
	 * - `SetAllowIPv6()`
	 * There will be allowed only defined values.
	 * @param  \string[] $allowedHostnames,...
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowedHostnames ($allowedHostnames) {
		if (!is_array($allowedHostnames)) 
			$allowedHostnames = func_get_args();
		$this->allowedHostnames = $allowedHostnames;
		return $this;
	}
	
	/**
	 * Get allowed port numbers. 
	 * No port number or any port number is allowed by default.
	 * This overwrites configuration by `SetAllowPorts()`.
	 * To allow some specific ports and also no port definition,
	 * define port values to allow and empty string for no port definition option.
	 * @return \string[]
	 */
	public function GetAllowedPorts () {
		return $this->allowedPorts;
	}

	/**
	 * Set allowed port numbers. 
	 * No port number or any port number is allowed by default.
	 * This overwrites configuration by `SetAllowPorts()`.
	 * To allow some specific ports and also no port definition,
	 * define port values to allow and empty string for no port definition option.
	 * @param  \string[]|\int[] $allowedPorts,...
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetAllowedPorts ($allowedPorts) {
		if (!is_array($allowedPorts)) 
			$allowedPorts = func_get_args();
		$allowedPortsStrArr = [];
		foreach ($allowedPorts as $allowedPort)
			$allowedPortsStrArr[] = (string) $allowedPort;
		$this->allowedPorts = $allowedPortsStrArr;
		return $this;
	}
	
	/**
	 * Get allowed absolute url schemes.
	 * Schemes `http,https,ftp,ftps` are allowed by default.
	 * @return \string[]
	 */
	public function GetAllowedSchemes () {
		return $this->allowedSchemes;
	}

	/**
	 * Set allowed absolute url schemes. 
	 * Schemes `http,https,ftp,ftps` are allowed by default.
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
	 * No DNS validation by default.
	 * @return string|bool
	 */
	public function GetDnsValidation () {
		return $this->dnsValidation;
	}

	/**
	 * Sets the DNS validation option. 
	 * No DNS validation by default.
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
	 * Get pattern for URL validation.
	 * @return string|NULL
	 */
	public function GetPattern () {
		if ($this->pattern === NULL)
			$this->preparePatternAndBackReferenceIndexes();
		return $this->pattern;
	}

	/**
	 * Set pattern for URL validation.
	 * @param  string|NULL $pattern 
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetPattern ($pattern) {
		$this->pattern = $pattern;
		return $this;
	}
	
	/**
	 * Get back reference index for completed pattern 
	 * to check hostname against allowed values.
	 * @return int|NULL
	 */
	public function GetBackReferencePosHostname () {
		if ($this->backReferencePosHostname === NULL)
			$this->preparePatternAndBackReferenceIndexes();
		return $this->backReferencePosHostname;
	}

	/**
	 * Set back reference index for completed pattern 
	 * to check hostname against allowed values.
	 * @param  int $backReferencePosHostname 
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetBackReferencePosHostname ($backReferencePosHostname) {
		$this->backReferencePosHostname = $backReferencePosHostname;
		return $this;
	}

	/**
	 * Get back reference index for completed pattern 
	 * to check port against allowed values.
	 * @return int|NULL
	 */
	public function GetBackReferencePosPort () {
		if ($this->backReferencePosPort === NULL)
			$this->preparePatternAndBackReferenceIndexes();
		return $this->backReferencePosPort;
	}

	/**
	 * Set back reference index for completed pattern 
	 * to check port against allowed values.
	 * @param  int $backReferencePosPort 
	 * @return \MvcCore\Ext\Forms\Validators\Url
	 */
	public function SetBackReferencePosPort ($backReferencePosPort) {
		$this->backReferencePosPort = $backReferencePosPort;
		return $this;
	}

	/**
	 * Validate URI string by regular expression and optionally by DNS check.
	 * @param  string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|NULL  Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$result = NULL;
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if ($rawSubmittedValue === '') return NULL;
		
		while (preg_match("#%[0-9a-zA-Z]{2}#", $rawSubmittedValue)) {
			if (json_encode(rawurldecode($rawSubmittedValue)) === FALSE) break;
			$rawSubmittedValue = rawurldecode($rawSubmittedValue);
		}
		$rawSubmittedValue = str_replace('%', '%25', $rawSubmittedValue);

		$this->preparePatternAndBackReferenceIndexes();
		
		$urlIsValid = (bool) preg_match_all($this->pattern, $rawSubmittedValue, $matches);
		if (!$urlIsValid) {
			$rawSubmittedValue = NULL;
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_URL)
			);
		} else {
			if (count($this->allowedHostnames) > 0) {
				$rawHostname = $matches[$this->backReferencePosHostname][0];
				if (!in_array($rawHostname, $this->allowedHostnames, TRUE)) {
					$rawSubmittedValue = NULL;
					$this->field->AddValidationError(
						static::GetErrorMessage(self::ERROR_URL)
					);
				}
			}
			if ($rawSubmittedValue !== NULL && count($this->allowedPorts) > 0) {
				$rawPort = ltrim($matches[$this->backReferencePosPort][0], ':');
				if (!in_array($rawPort, $this->allowedPorts, TRUE)) {
					$rawSubmittedValue = NULL;
					$this->field->AddValidationError(
						static::GetErrorMessage(self::ERROR_URL)
					);
				}
			}
		}

		if ($rawSubmittedValue !== NULL && $this->dnsValidation) {
			/*
			 * PHP 5.6 BUG
			 * @see https://bugs.php.net/bug.php?id=73192
			$queryPos = mb_strpos($rawSubmittedValue, '?');
			if ($queryPos !== FALSE) {
				$rawSubmittedValueWithoutQs = mb_substr($rawSubmittedValue, 0, $queryPos);
			} else {
				$rawSubmittedValueWithoutQs = $rawSubmittedValue;
			}
			$rawHostname = parse_url($rawSubmittedValueWithoutQs, PHP_URL_HOST);
			*/
			$rawHostname = $matches[$this->backReferencePosHostname][0];
			if (!is_string($rawHostname) || !checkdnsrr($rawHostname, $this->dnsValidation)) {
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

	/**
	 * Prepare pattern and back reference indexes to validate URL only once.
	 * @return void
	 */
	protected function preparePatternAndBackReferenceIndexes () {
		$protocols = implode('|', $this->allowedSchemes);
		if (($this->allowProtocol & self::ALLOW_PROTOCOL_ABSOLUTE) != 0) {
			$protocol = '('.$protocols.')://';
		} else if (($this->allowProtocol & self::ALLOW_PROTOCOL_RELATIVE) != 0) {
			$protocol = '(?:('.$protocols.'):)?//';
		} else {
			$protocol = '(?:('.$protocols.')://)?';
		}

		$auth = '';
		if ($this->allowBasicAuth) {
			$auth = static::PATTERN_PART_AUTH;
			$this->backReferencePosHostname = 5;
			$this->backReferencePosPort = 9;
		} else {
			$this->backReferencePosHostname = 2;
			$this->backReferencePosPort = 6;
		}
		

		$hostname = '';
		$hostnameParts = [];
		if ($this->allowDomains)
			$hostnameParts[] = static::PATTERN_PART_DOMAIN;
		if ($this->allowIPv4)
			$hostnameParts[] = static::PATTERN_PART_IPV4;
		if ($this->allowIPv6)
			$hostnameParts[] = static::PATTERN_PART_IPV6;
		if (count($hostnameParts) > 0) {
			$hostname = '(' . implode('|', $hostnameParts) . ')';
		} else {
			$hostname = '(' . implode('|', [
				static::PATTERN_PART_DOMAIN,
				static::PATTERN_PART_IPV4,
				static::PATTERN_PART_IPV6
			]) . ')';
		}
		

		$port = '';
		if (count($this->allowedPorts) > 0 || $this->allowPorts) {
			$port = static::PATTERN_PART_PORT;
		} else {
			$this->backReferencePosPort = NULL;
		}


		$this->pattern = str_replace([
			'{%protocol}', '{%auth}', '{%hostname}', '{%port}', 
			'{%path}', '{%query}', '{%fragment}'
		], [
			$protocol, $auth, $hostname, $port, 
			static::PATTERN_PART_PATH, static::PATTERN_PART_QUERY, static::PATTERN_PART_FRAGMENT
		], static::PATTERN_ALL);
	}
}
