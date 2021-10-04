<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP;

class Creditnote {
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/creditnote?wsdl');
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
	 * Get PDF
	 *
	 * @access public
	 * @return array $fileinfo
	 */
	public function get_pdf() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/creditnote?wsdl');
		return $client->get_pdf($this->id);
	}

	/**
	 * Get by id
	 *
	 * @access public
	 * @return \Tigron\Creditnote $creditnote
	 */
	public static function get_by_id($id) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/creditnote?wsdl');
		$details = $client->get_by_id($id);
		$creditnote = new self();
		$creditnote->id = $details['id'];
		$creditnote->details = $details;

		return $creditnote;
	}

	/**
	 * Get by number
	 *
	 * @access public
	 * @param string $number
	 * @return Creditnote $creditnote
	 */
	public static function get_by_number($number) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/creditnote?wsdl');
		$details = $client->get_by_number($number);
		$creditnote = new self();
		$creditnote->id = $details['id'];
		$creditnote->details = $details;

		return $creditnote;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $creditnotes
	 */
	public static function get_by_user(\Tigron\User $user) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/creditnote?wsdl');
		$data = $client->get_by_user($user->id);
		$creditnotes = [];
		foreach ($data as $details) {
			$creditnote = new self();
			$creditnote->details = $details;
			$creditnote->id = $details['id'];
			$creditnotes[] = $creditnote;
		}
		return $creditnotes;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $creditnotes
	 */
	public static function get_paged($sort, $direction, $page, $extra_conditions) {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/creditnote?wsdl');
		$data = $client->get_paged($sort, $direction, $page, $extra_conditions);
		$creditnotes = [];
		foreach ($data as $details) {
			$creditnote = new self();
			$creditnote->details = $details;
			$creditnote->id = $details['id'];
			$creditnotes[] = $creditnote;
		}
		return $creditnotes;
	}
}
