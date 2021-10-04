<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Util {


	/**
	 * Check if a TLD is sellable or not. A user can submit any
	 * domain, use this method to check it you can provide it.
	 *
	 * @access public
	 * @param string $domain The domain name to check
	 * @param string $tld The TLD under which the domain name should be checked
	 * @return int $valid 1 or 0, 1 is true, 0 is false
	 */
	public static function domain_sellable($domain, $tld) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/util?wsdl');
		return $client->domain_sellable($domain, $tld);
	}

	/**
	 * Check the availability of a domain.
	 *
	 * This method should also work on TLD's that are not activated
	 * in your account.
	 *
	 * @access public
	 * @param string $domain The domain name to check
	 * @param string $tld The TLD under which the domain name should be checked
	 * @return int $valid 1 or 0, 1 is true, 0 is false
	 */
	public function domain_available($domain, $tld) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/util?wsdl');
		return $client->domain_available($domain, $tld);
	}

	/**
	 * Check if a TLD is sellable or not. A user can submit any
	 * domain, use this method to check it you can provide it.
	 *
	 * @access public
	 * @param string $domain The domain name to check
	 * @param string $tld The TLD under which the domain name should be checked
	 * @return int $valid 1 or 0, 1 is true, 0 is false
	 */
	public static function domain_available_and_sellable($domain, $tld) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/util?wsdl');
		return $client->domain_available_and_sellable($domain, $tld);
	}
}
