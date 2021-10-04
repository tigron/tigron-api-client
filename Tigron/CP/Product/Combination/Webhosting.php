<?php
/**
 * Tigron Front-user
 *
 * This file is a part of the Tigron Application 'Front-User'
 *
 * @package Tigron
 */
namespace Tigron\CP\Product\Combination;

class Webhosting extends \Tigron\CP\Product {

	/**
	 * Get mysql product
	 *
	 * @access public
	 */
	public function get_mysql_product() {
		$client = \Tigron\CP\Client\Soap::get('http://api.tigron.net/soap/product?wsdl');
		try {
			$product_id = $client->get_mysql_product($this->id);
		} catch (\Exception $e) {
			throw new \Exception('This product has no mysql child product');
		}
		return \Tigron\CP\Product::get_by_id($product_id);
	}

}
