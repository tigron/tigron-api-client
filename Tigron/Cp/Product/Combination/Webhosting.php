<?php
/**
 * Product\Combination\Webhosting class
 */

namespace Tigron\Cp\Product\Combination;

class Webhosting extends \Tigron\Cp\Product {

	/**
	 * Get mysql product
	 *
	 * @access public
	 */
	public function get_mysql_product() {
		$client = \Tigron\Cp\Client\Soap::get('product');
		try {
			$product_id = $client->get_mysql_product($this->id);
		} catch (\Exception $e) {
			throw new \Exception('This product has no mysql child product');
		}
		return \Tigron\Cp\Product::get_by_id($product_id);
	}

}
