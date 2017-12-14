<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Reseller {

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
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/reseller?wsdl');
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
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/reseller?wsdl');
		if (isset($this->details['id']) AND $this->details['id'] > 0) {
			$this->details = $client->update($this->details['id'], $this->details);
		} else {
			$this->id = $client->insert($this->details);
		}
		$this->get_details();
	}

	/**
	 * Get a Reseller by ID
	 *
	 * @access public
	 * @param int $id
	 * @Return Reseller
	 */
	public static function get_by_id($id) {
		$reseller = new Reseller($id);
		return $reseller;
	}

	/**
	 * Get all resellers
	 *
	 * @access public
	 * @return array Reseller
	 */
	public static function get_all() {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/reseller?wsdl');
		$reseller_info = $client->get_all();
		$resellers = [];
		foreach ($reseller_info as $info) {
			$reseller = new self();
			$reseller->id = $info['id'];
			$reseller->details = $info;
			$resellers[] = $reseller;
		}
		return $resellers;
	}

}
