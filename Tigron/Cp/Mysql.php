<?php
/**
 * Mysql Class
 */

namespace Tigron\Cp;

class Mysql {
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
		$client = \Tigron\Cp\Client\Soap::get('mysql');
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
	 * Get by id
	 *
	 * @access public
	 * @return \Tigron\Mysql $mysql
	 */
	public static function get_by_id($id) {
		$client = \Tigron\Cp\Client\Soap::get('mysql');
		$details = $client->get_by_id($id);
		$mysql = new self();
		$mysql->id = $details['id'];
		$mysql->details = $details;

		return $mysql;
	}

	/**
	 * Get by product
	 *
	 * @access public
	 * @param \Tigron\Cp\Product $product
	 * @return array $mysqls
	 */
	public static function get_by_product(\Tigron\Cp\Product $product) {
		$client = \Tigron\Cp\Client\Soap::get('mysql');
		$data = $client->get_mysql_by_product($product->id);
		$mysqls = [];
		foreach ($data as $details) {
			$mysql = new self();
			$mysql->details = $details;
			$mysql->id = $details['id'];
			$mysqls[] = $mysql;
		}
		return $mysqls;
	}
}
