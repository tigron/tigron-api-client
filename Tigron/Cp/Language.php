<?php
/**
 * Language class
 */

namespace Tigron\Cp;

class Language {
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
	 * Country cache
	 *
	 * @access private
	 */
	private static $cache = [];

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
		$client = \Tigron\Cp\Client\Soap::get('language');
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
	 * @return \Tigron\Language $contact
	 */
	public static function get_by_id($id) {
		if (!isset(self::$cache[$id])) {
			$client = \Tigron\Cp\Client\Soap::get('language');
			$details = $client->get_by_id($id);
			$language = new self();
			$language->id = $details['id'];
			$language->details = $details;
			self::$cache[$id] = $language;
		}

		return self::$cache[$id];
	}

	/**
	 * Get all
	 *
	 * @access public
	 * @return array $countries
	 */
	public static function get_all() {
		$client = \Tigron\Cp\Client\Soap::get('language');
		$data = $client->get_all();
		$countries = [];
		foreach ($data as $details) {
			$language = new self();
			$language->id = $details['id'];
			$language->details = $details;
			$countries[] = $language;
		}

		return $countries;
	}

	/**
	 * Get grouped
	 *
	 * @access public
	 * @return array $countries
	 */
	public static function get_grouped() {
		$countries = self::get_all();
		$grouped = [ 'Europe' => [], 'World' => [] ];
		foreach ($countries as $language) {
			if ($language->european_continent) {
				$grouped['Europe'][] = $language;
			} else {
				$grouped['World'][] = $language;
			}
		}
		return $grouped;
	}
}
