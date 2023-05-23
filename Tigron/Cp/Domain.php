<?php
/**
 * Domain class
 */

namespace Tigron\Cp;

class Domain {
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
		$client = \Tigron\Cp\Client\Soap::get('domain');
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
	 * Get by product
	 *
	 * @access public
	 * @param \Tigron\Cp\Product $product
	 * @return array $mysqls
	 */
	public static function get_by_product(\Tigron\Cp\Product $product) {
		$client = \Tigron\Cp\Client\Soap::get('domain');
		$data = $client->get_by_product($product->id);

		$domain = new self();
		$domain->details = $data;
		$domain->id = $data['id'];
		return $domain;
	}

	/**
	 * Get by product
	 *
	 * @access public
	 * @param \Tigron\Cp\Product $product
	 * @return array $mysqls
	 */
	public static function get_by_name_tld($name, $tld) {
		$client = \Tigron\Cp\Client\Soap::get('domain');
		$data = $client->get_by_name_tld($name, $tld);

		$domain = new self();
		$domain->details = $data;
		$domain->id = $data['id'];
		return $domain;
	}	
}
