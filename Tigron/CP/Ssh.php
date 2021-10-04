<?php
/**
 * Tigron Front-user
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Ssh {

	/**
	 * ID
	 *
	 * @access public
	 * @var int $id
	 */
	public $user_id;

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
	public function __construct($user_id = null) {
		if ($user_id !== null) {
			$this->user_id = $user_id;
			$this->get_details();
		}
	}

	/**
	 * Get the details via Soap
	 *
	 * @access private
	 */
	private function get_details() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/ssh?wsdl');
		$this->details = $client->get_by_user($this->user_id);
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/ssh?wsdl');
		if (isset($this->user_id)) {
			$client->update($this->user_id, $this->details);
		} else {
			throw new \Exception('Cannot create a new SSH product');
		}
		$this->get_details();
	}

	/**
	 * Generate key_pair
	 *
	 * @access public
	 */
	public function generate_key_pair() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/ssh?wsdl');
		$client->generate_key_pair();
		$this->get_details();
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $contacts
	 */
	public static function get_by_user(\Tigron\CP\User $user) {
		return new self($user->id);
	}
}
