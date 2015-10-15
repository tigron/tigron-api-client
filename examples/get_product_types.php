<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

/**
 * Fetch all the product_types from Tigron Control panel
 * The product_types will be grouped based on their category
 */
$categories = Tigron\Product\Type\Category::get_all();

foreach ($categories as $category) {
	echo $category->name . "\n";

	$product_types = $category->get_product_types();
	foreach ($product_types as $product_type) {
		echo "\t" . $product_type->name . "\n";
	}
}
