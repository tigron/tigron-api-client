<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Product {
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/product?wsdl');
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/product?wsdl');
		if (isset($this->details['id']) AND $this->details['id'] > 0) {
			$this->details = $client->update($this->details['id'], $this->details);
		} else {
			$this->id = $client->insert($this->details);
		}
		$this->get_details();
	}

	/**
	 * Get

	/**
	 * Get by id
	 *
	 * @access public
	 * @return \Tigron\Product $product
	 */
	public static function get_by_id($id) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/product?wsdl');
		$details = $client->get_by_id($id);
		$product = new self();
		$product->id = $details['id'];
		$product->details = $details;

		return $product;
	}

	/**
	 * Get by user category
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @param \Tigron\Product\Category $category
	 * @return array $products
	 */
	public static function get_by_user_category(\Tigron\CP\User $user, \Tigron\CP\Product\Type\Category $category) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/product?wsdl');
		$data = $client->get_by_user_category($user->id, $category->id);
		$products = [];
		foreach ($data as $details) {
			$classname = '\Tigron\CP\\' . str_replace('_', '\\', $details['classname']);

			if (class_exists($classname)) {
				$product = new $classname;
			} else {
				$product = new self();
			}
			$product->details = $details;
			$product->id = $details['id'];
			$products[] = $product;
		}
		return $products;
	}
}
