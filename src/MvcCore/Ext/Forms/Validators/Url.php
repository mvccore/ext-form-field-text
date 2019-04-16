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
	const VALIDATE_DNS_TYPE_NONE = FALSE;
    const VALIDATE_DNS_TYPE_ANY = 'ANY';
    const VALIDATE_DNS_TYPE_A = 'A';
    const VALIDATE_DNS_TYPE_A6 = 'A6';
    const VALIDATE_DNS_TYPE_AAAA = 'AAAA';
    const VALIDATE_DNS_TYPE_CNAME = 'CNAME';
    const VALIDATE_DNS_TYPE_MX = 'MX';
    const VALIDATE_DNS_TYPE_NAPTR = 'NAPTR';
    const VALIDATE_DNS_TYPE_NS = 'NS';
    const VALIDATE_DNS_TYPE_PTR = 'PTR';
    const VALIDATE_DNS_TYPE_SOA = 'SOA';
    const VALIDATE_DNS_TYPE_SRV = 'SRV';
    const VALIDATE_DNS_TYPE_TXT = 'TXT';

	const PATTERN = '~^
            (%s)://                                 # scheme
            (([\.\pL\pN-]+:)?([\.\pL\pN-]+)@)?      # basic auth
            (
                ([\pL\pN\pS\-\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?) # a domain name
                    |                                                 # or
                \d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}                    # an IP address
                    |                                                 # or
                \[
                    (?:(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){6})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:::(?:(?:(?:[0-9a-f]{1,4})):){5})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){4})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,1}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){3})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,2}(?:(?:[0-9a-f]{1,4})))?::(?:(?:(?:[0-9a-f]{1,4})):){2})(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,3}(?:(?:[0-9a-f]{1,4})))?::(?:(?:[0-9a-f]{1,4})):)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,4}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:(?:(?:(?:[0-9a-f]{1,4})):(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9]))\.){3}(?:(?:25[0-5]|(?:[1-9]|1[0-9]|2[0-4])?[0-9])))))))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,5}(?:(?:[0-9a-f]{1,4})))?::)(?:(?:[0-9a-f]{1,4})))|(?:(?:(?:(?:(?:(?:[0-9a-f]{1,4})):){0,6}(?:(?:[0-9a-f]{1,4})))?::))))
                \]  # an IPv6 address
            )
            (:[0-9]+)?                              # a port (optional)
            (?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*      # a path
            (?:\? (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a query (optional)
            (?:\# (?:[\pL\pN\-._\~!$&\'()*+,;=:@/?]|%%[0-9A-Fa-f]{2})* )?   # a fragment (optional)
        $~ixu';

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
		self::ERROR_DNS	=> "The host in field {0} could not be resolved.",
	];

	/**
	 * Validate URI string by PHP `filter_var($rawSubmittedValue, FILTER_VALIDATE_URL);`.
	 * @param string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$result = NULL;
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if ($rawSubmittedValue === '') 
			return NULL;
		while (preg_match("#%[0-9a-zA-Z]{2}#", $rawSubmittedValue)) 
			$rawSubmittedValue = rawurldecode($rawSubmittedValue);
		$queryPos = mb_strpos($rawSubmittedValue, '?');
		if ($queryPos !== FALSE) {
			$rawSubmittedValueWithoutQs = mb_substr($rawSubmittedValue, 0, $queryPos);
		} else {
			$rawSubmittedValueWithoutQs = $rawSubmittedValue;
		}





		$checkDns = static::VALIDATE_DNS_TYPE_NONE;
		$relativeProtocol = FALSE;
		$schemes = ['http', 'https'];





		$pattern = $relativeProtocol 
			? str_replace('(%s):', '(?:(%s):)?', static::PATTERN) 
			: static::PATTERN;
        $pattern = sprintf($pattern, implode('|', $schemes));
		
        if (!preg_match($pattern, $rawSubmittedValueWithoutQs)) {
			$rawSubmittedValue = NULL;
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_URL)
			);
        } else if ($checkDns) {
            if (!\in_array($checkDns, [
                static::VALIDATE_DNS_TYPE_ANY,
                static::VALIDATE_DNS_TYPE_A,
                static::VALIDATE_DNS_TYPE_A6,
                static::VALIDATE_DNS_TYPE_AAAA,
                static::VALIDATE_DNS_TYPE_CNAME,
                static::VALIDATE_DNS_TYPE_MX,
                static::VALIDATE_DNS_TYPE_NAPTR,
                static::VALIDATE_DNS_TYPE_NS,
                static::VALIDATE_DNS_TYPE_PTR,
                static::VALIDATE_DNS_TYPE_SOA,
                static::VALIDATE_DNS_TYPE_SRV,
                static::VALIDATE_DNS_TYPE_TXT,
            ], TRUE)) {
				/*$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_URL_INVALID_OPTION)
				);*/
                throw new \Exception(sprintf(
					'Invalid value for option "checkDNS" in constraint %s', 
					$checkDns
				));
            }

            $host = parse_url($rawSubmittedValueWithoutQs, PHP_URL_HOST);
            if (!is_string($host) || !checkdnsrr($host, $checkDns)) {
				$rawSubmittedValue = NULL;
				$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_DNS)
				);
            }
        }
		//xxx($rawSubmittedValue);





		if ($rawSubmittedValue !== NULL) 
			$result = $rawSubmittedValue;
		return $result;
	}

	/**
	 * Validate URI string by PHP `filter_var($rawSubmittedValue, FILTER_VALIDATE_URL);`.
	 * @param string|array $rawSubmittedValue Raw submitted value from user.
	 * @return string|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function _Validate ($rawSubmittedValue) {
		$result = NULL;
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if ($rawSubmittedValue === '') 
			return NULL;
		while (mb_strpos($rawSubmittedValue, '%') !== FALSE)
			$rawSubmittedValue = rawurldecode($rawSubmittedValue);
		$safeValue = filter_var($rawSubmittedValue, FILTER_VALIDATE_URL);
		x($rawSubmittedValue);
		xxx($safeValue);
		if ($safeValue !== FALSE) {
			$result = $safeValue;
		} else {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_URL)
			);
		}
		return $result;
	}
}
