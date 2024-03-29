<?php
/**
 * Client\Soap class
 */

namespace Tigron\Cp\Client;

class Soap {

	/**
	 * @var Soapclient
	 * @access private
	 */
	public $soapclient = null;

	/**
	 * Headers
	 *
	 * @access private
	 * @var array $headers
	 */
	private $headers = [];

	/**
	 * Clients
	 *
	 * @access private
	 * @var array $clients
	 */
	private static $clients = [];

	/**
	 * Constructor
	 *
	 * @param string soap_service
	 * @access private
	 */
	public function __construct($soap_service) {
		$soap_url = \Tigron\Cp\Config::$tigron_api_url . $soap_service . '?wsdl';

		$options = [
			'trace' => true,
			'compression' => SOAP_COMPRESSION_ACCEPT | \SOAP_COMPRESSION_GZIP | 9,
			'cache_wsdl' => \WSDL_CACHE_DISK,
		];

		$this->soapclient = new \SoapClient($soap_url, $options);
	}

	/**
	 * Set headers
	 *
	 * @access public
	 * @param array $headers
	 */
	public function set_headers($headers) {
		$this->headers = $headers;
	}

	/**
	 * Call a Soap function
	 *
	 * @access protected
	 * @param string $name
	 * @param array $arguments
	 * @return mixed $soap_answer
	 */
	public function __call($name, $arguments) {
		$headers = $this->generate_soap_headers();
		$this->set_headers($headers);

		try {
			return $this->decode($this->soapclient->__soapCall($name, $arguments, [], $this->headers));
		} catch (\SoapFault $sf) {
			throw new \Exception('Error in soap call: ' . $sf->faultstring);
		}
	}

	/**
	 * Due to PHP being typeless, arrays end up in a very strange mangled
	 * format. This function unmangles it. Looks like a bug in the SOAP
	 * bindings though.
	 *
	 * @param mixed Object to unmangle
	 * @return mixed Unmangled object
	 * @access public
	 */
	public function decode($obj) {
		if (is_array($obj)) {
			foreach ($obj as $key => $val) {
				$obj[$key] = self::decode($val);
			}
			return $obj;
		}

		if (!is_object($obj) || !isset($obj->item)) {
			return $obj;
		}

		$array = array();
		if (is_array($obj->item)) {
			foreach ($obj->item as $item) {
				$array[$item->key] = self::decode($item->value);
			}
		} else {
			$array[$obj->item->key] = self::decode($obj->item->value);
		}

		return $array;
	}

	/**
	 * Authenticate Reseller
	 *
	 * @access public
	 * @param string $username
	 * @param string $password
	 */
	public function authenticate_user($username, $password) {
		$this->user_credentials = array($username, $password);
	}

	/**
	 * Get the functions for this Soap_Client
	 *
	 * @access public
	 * @return string
	 */
	public function get_functions() {
		if ($this->soapclient == null) {
			throw new Exception('Unknown Soap_Client');
		}
		return $this->soapclient->__getfunctions();
	}

	/**
	 * Generate soapcall headers
	 *
	 * @access private
	 * @return mixed
	 */
	private function generate_soap_headers() {
		$headers = [];
		$headers[] = new \SoapHeader('http://www.tigron.net/ns/', 'authenticate_user', [ \Tigron\Cp\Config::$tigron_username, \Tigron\Cp\Config::$tigron_password ]);
		return $headers;
	}

	/**
	 * Get the client
	 *
	 * @access public
	 * @param string $url
	 * @return \Tigron\Cp\Client $client
	 */
	public static function get($url) {
		if (isset(self::$clients[$url])) {
			return self::$clients[$url];
		}
		$client = new self($url);
		self::$clients[$url] = $client;
		return $client;
	}
}
