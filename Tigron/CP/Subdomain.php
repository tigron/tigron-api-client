<?php
/**
 * Tigron
 *
 * This file is a part of the Tigron Api Client
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Subdomain {
	/**
	 * ID
	 *
	 * @access public
	 * @var int $id
	 */
	public $id;

	/**
	 * Details
	 *
	 * @var $details
	 * @access private
	 */
	public $details;

	/**
	 * Contructor
	 *
	 * @access private
	 * @param string $username
	 * @param string $password
	 */
	public function __construct($id = null) {
		if ($id !== null) {
			$this->id = $id;
			$this->get_details();
		}
	}

	/**
	 * Get the details via Soap
	 *
	 * @access private
	 */
	private function get_details() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/subdomain?wsdl');
		$this->details = $client->get_by_id($this->id);
	}

	/**
	 * Get a field
	 *
	 * @access public
	 * @param string $field
	 * @return mixed
	 */
	public function __get($key) {
		return $this->details[$key];
	}

	/**
	 * Set a field
	 *
	 * @access public
	 * @param string $key
	 * @param mixes value
	 */
	public function __set($key, $value) {
		$this->details[$key] = $value;
	}

	/**
	 * Isset
	 *
	 * @access public
	 * @param string $key
	 * @return bool $isset
	 */
	public function __isset($key) {
		if (isset($this->details[$key])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Save
	 *
	 * @access public
	 */
	public function save() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/subdomain?wsdl');
		if (isset($this->id)) {
			$client->update($this->id, $this->details);
		} else {
			$this->id = $this->insert($this->id, $this->details);
		}
	}

	/**
	 * Get by id
	 *
	 * @access public
	 * @return \Tigron\Product $product
	 */
	public static function get_by_id($id) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/subdomain?wsdl');
		$details = $client->get_by_id($id);
		$subdomain = new self();
		$subdomain->id = $details['id'];
		$subdomain->details = $details;
		return $subdomain;
	}

	/**
	 * Get by domain tld
	 *
	 * @access public
	 * @param string $domain
	 * @param string $tld
	 * @return array $subdomains
	 */
	public static function get_by_domain_tld($domain, $tld) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/subdomain?wsdl');
		$data = $client->get_by_domain_tld($domain, $tld);
		$subdomains = [];
		foreach ($data as $details) {
			$subdomain = new self();
			$subdomain->details = $details;
			$subdomain->id = $details['id'];
			$subdomains[] = $subdomain;
		}
		return $subdomains;
	}

	/**
	 * Get by domain tld
	 *
	 * @access public
	 * @param string $name
	 * @param string $domain
	 * @param string $tld
	 * @return array $subdomains
	 */
	public static function get_by_name_domain_tld($name, $domain, $tld) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/subdomain?wsdl');
		$details = $client->get_by_name_domain_tld($name, $domain, $tld);

		$subdomain = new self();
		$subdomain->details = $details;
		$subdomain->id = $details['id'];
		return $subdomain;
	}
}
