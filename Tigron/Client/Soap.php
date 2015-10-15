<?php
/**
 * Client_soap class
 *
 * @author Gerry Demaret <gerry@tigron.be>
 * @author Christophe Gosiau <christophe@tigron.be>
 */

namespace Tigron;

 class Client_Soap extends \Client_Soap {


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
		return parent::__call($name, $arguments, $headers);
	}

	/**
	 * Generate soapcall headers
	 *
	 * @access private
	 * @return mixed
	 */
	private function generate_soap_headers() {
		$config = \Config::Get();
		$headers = [];
		$headers[] = new \SoapHeader('http://www.tigron.net/ns/', 'authenticate_user', [ Config::$tigron_username, Config::$tigron_password ]);
		return $headers;
	}
}
