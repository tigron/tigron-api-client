<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Sms {

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
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/sms?wsdl');
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
	 * @return \Tigron\Cp\Sms $sms
	 */
	public static function get_by_id($id) {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/sms?wsdl');
		$details = $client->get_by_id($id);
		$mysql = new self();
		$mysql->id = $details['id'];
		$mysql->details = $details;

		return $mysql;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $mysqls
	 */
	public static function get_overview() {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/sms?wsdl');
		$user = \Tigron\CP\User::Get();		
		$data = $client->get_overview($user->id);
		return $data;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $mysqls
	 */
	public static function get_history_by_user(\Tigron\CP\User $user) {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/sms?wsdl');
		$user = \Tigron\CP\User::Get();		
		$data = $client->get_history_by_user($user->id);
		$result = [];
		foreach ($data as $row) {
			$sms = new self();
			$sms->id = $row['id'];
			$sms->details = $row;
			$result[] = $sms;
		}
		return $result;
	}

	/**
	 * Send an sms message
	 *
	 * @access public
	 * @param string $from
	 * @param string $to
	 * @param string $message
	 */
	public function send_sms($from, $to, $message) {
		$client = new \Tigron\CP\Client\Soap('http://api.tigron.net/soap/sms?wsdl');
		$user = \Tigron\CP\User::Get();		
		return $client->send_sms($user->id, $from, $to, $message);
	}	
}
