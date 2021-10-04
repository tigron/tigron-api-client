<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Contact {
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/contact?wsdl');
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
	 * @return \Tigron\Contact $contact
	 */
	public static function get_by_id($id) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/contact?wsdl');
		$details = $client->get_by_id($id);
		$contact = new self();
		$contact->id = $details['id'];
		$contact->details = $details;

		return $contact;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $contacts
	 */
	public static function get_by_user(\Tigron\User $user) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/contact?wsdl');
		$data = $client->get_by_user($user->id);
		$contacts = [];
		foreach ($data as $details) {
			$contact = new self();
			$contact->details = $details;
			$contact->id = $details['id'];
			$contacts[] = $contact;
		}
		return $contacts;
	}
}
