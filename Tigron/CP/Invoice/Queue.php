<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP\Invoice;

class Queue {
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/invoice_queue?wsdl');
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
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/invoice_queue?wsdl');
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
	 * @return Invoice_Queue $invoice_queue
	 */
	public static function get_by_id($id) {
		return new self($id);
	}

	/**
	 * Get all
	 *
	 * @access public
	 * @return array $users
	 */
	public static function get_all() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/invoice_queue?wsdl');
		$details = $client->get_by_reseller(\Tigron\CP\User::get()->reseller_id);
		$users = array();
		foreach ($details as $detail) {
			$temp_invoice_queue = new self();
			$temp_invoice_queue->id = $detail['id'];
			$temp_invoice_queue->details = $detail;
			$invoice_queue[] = $temp_invoice_queue;
		}
		return $invoice_queue;
	}
}
