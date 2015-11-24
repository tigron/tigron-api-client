<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP\Product;

class Type {
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
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/product_type?wsdl');
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
	 * Save function
	 *
	 * @access public
	 */
	public function save() {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/product_type?wsdl');
		if (isset($this->details['id']) AND $this->details['id'] > 0) {
			$this->details = $client->update($this->details['id'], $this->details);
		} else {
			$this->id = $client->insert($this->details);
		}
		$this->get_details();
	}

	/**
	 * Get by id
	 *
	 * @access public
	 * @param int $id
	 * @return Product_Type $product_type
	 */
	public static function get_by_id($id) {
		return new self($id);
	}

	/**
	 * Get by id
	 *
	 * @access public
	 * @return Product_Type $product_type
	 */
	public static function get_by_product_type_category(\Tigron\CP\Product\Type\Category $product_type_category) {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/product_type?wsdl');
		$details = $client->get_by_product_type_category_reseller($product_type_category->id, \Tigron\CP\User::get()->reseller_id);

		$types = [];
		foreach ($details as $detail) {
			$type = new self();
			$type->id = $detail['id'];
			$type->details = $detail;
			$types[] = $type;
		}

		return $types;
	}

}
