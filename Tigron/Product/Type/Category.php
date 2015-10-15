<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\Product\Type;

class Category {
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
		$client = new Tigron\Client\Soap('http://api.tigron.net/soap/product_type_category?wsdl');
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
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/product_type_category?wsdl');
		if (isset($this->details['id']) AND $this->details['id'] > 0) {
			$this->details = $client->update($this->details['id'], $this->details);
		} else {
			$this->id = $client->insert($this->details);
		}
		$this->get_details();
	}

	/**
	 * Get product types
	 *
	 * @access public
	 */
	public function get_product_types() {
		return \Tigron\Product\Type::get_by_product_type_category($this);
	}

	/**
	 * Get all
	 *
	 * @access public
	 * @return array $users
	 */
	public static function get_all() {
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/product_type_category?wsdl');
		$details = $client->get_all();
		$categories = array();
		foreach ($details as $detail) {
			$temp_ptc = new self();
			$temp_ptc->id = $detail['id'];
			$temp_ptc->details = $detail;
			$categories[] = $temp_ptc;
		}
		return $categories;
	}

	/**
	 * Get by identifier
	 *
	 * @access public
	 * @param string $identifier
	 * @return Category
	 */
	public static function get_by_identifier($identifier) {
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/product_type_category?wsdl');
		$details = $client->get_by_identifier($identifier);

		$category = new self();
		$category->details = $details;
		$category->id = $details['id'];
		return $category;
	}
}
