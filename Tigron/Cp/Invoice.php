<?php
/**
 * Invoice Class
 */

namespace Tigron\Cp;

class Invoice {
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
		$client = \Tigron\Cp\Client\Soap::get('invoice');
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
		$client = \Tigron\Cp\Client\Soap::get('invoice');
		return $client->get_pdf($this->id);
	}

	/**
	 * Get by id
	 *
	 * @access public
	 * @return \Tigron\Invoice $invoice
	 */
	public static function get_by_id($id) {
		$client = \Tigron\Cp\Client\Soap::get('invoice');
		$details = $client->get_by_id($id);
		$invoice = new self();
		$invoice->id = $details['id'];
		$invoice->details = $details;

		return $invoice;
	}


	/**
	 * Get by number
	 *
	 * @access public
	 * @param string $number
	 * @return Invoice $invoice
	 */
	public static function get_by_number($number) {
		$client = \Tigron\Cp\Client\Soap::get('invoice');
		$details = $client->get_by_number($number);
		$invoice = new self();
		$invoice->id = $details['id'];
		$invoice->details = $details;

		return $invoice;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $invoices
	 */
	public static function get_by_user(\Tigron\User $user) {
		$client = \Tigron\Cp\Client\Soap::get('invoice');
		$data = $client->get_by_user($user->id);
		$invoices = [];
		foreach ($data as $details) {
			$invoice = new self();
			$invoice->details = $details;
			$invoice->id = $details['id'];
			$invoices[] = $invoice;
		}
		return $invoices;
	}

	/**
	 * Get by user
	 *
	 * @access public
	 * @param \Tigron\User $user
	 * @return array $invoices
	 */
	public static function get_paged($sort, $direction, $page, $extra_conditions) {
		$client = \Tigron\Cp\Client\Soap::get('invoice');
		$data = $client->get_paged($sort, $direction, $page, $extra_conditions);
		$invoices = [];
		foreach ($data as $details) {
			$invoice = new self();
			$invoice->details = $details;
			$invoice->id = $details['id'];
			$invoices[] = $invoice;
		}
		return $invoices;
	}
}
