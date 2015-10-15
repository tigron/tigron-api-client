<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron;

class User {
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
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/user?wsdl');
		$this->details = $client->get_by_id($this->id);

		$customer_tigron_users = \Customer_Tigron_User::get_by_tigron_user($this);

		foreach ($customer_tigron_users as $customer_tigron_user) {
			$this->details['customer_id'] = $customer_tigron_user->customer_id;
		}
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
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/user?wsdl');
		if (isset($this->details['id']) AND $this->details['id'] > 0) {
			$this->details = $client->update($this->details['id'], $this->details);
		} else {
			$this->id = $client->insert($this->details);
		}
		$this->get_details();
	}

	/**
	 * Get by reseller
	 *
	 * @access public
	 * @param \Tigron\Reseller $reseller
	 * @return array $users
	 */
	public static function get_by_reseller(\Tigron\Reseller $reseller) {
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/user?wsdl');
		$details = $client->get_by_reseller($reseller->id);
		$users = array();
		foreach ($details as $detail) {
			$temp_user = new User();
			$temp_user->id = $detail['id'];
			$temp_user->details = $detail;
			$users[] = $temp_user;
		}
		return $users;
	}

	/**
	 * Get a Reseller by ID
	 *
	 * @access public
	 * @Return Reseller
	 */
	public static function get() {
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/user?wsdl');
		$info = $client->info();
		$user = new User();
		$user->id = $info['id'];
		$user->details = $info;
		return $user;
	}


	/**
	 * Get by ID
	 *
	 * @access public
	 * @param int $user_id
	 * @Return User $user
	 */
	public static function get_by_id($id) {
		$client = new \Tigron\Client\Soap('http://api.tigron.net/soap/user?wsdl');
		$info = $client->get_by_id($id);
		$user = new User();
		$user->id = $info['id'];
		$user->details = $info;
		return $user;
	}
}
