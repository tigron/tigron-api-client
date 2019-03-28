<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

/**
 * Fetch all the product_types from Tigron Control panel
 * The product_types will be grouped based on their category
 */
$webhosting = Tigron\CP\Product\Type\Category::get_by_identifier('webhosting');
$products = Tigron\CP\Product::get_by_user_category(Tigron\CP\User::get(), $webhosting);

foreach ($products as $product) {
	$product_type = \Tigron\CP\Product\Type::get_by_id($product->product_type_id);
	echo $product->domain . '.' . $product->tld . " (" . $product_type->name . ")\n";

	try {
		$mysql_product = $product->get_mysql_product();
	} catch (Exception $e) {
		echo "\t" . 'no databases' . "\n";
		continue;
	}

	$databases = \Tigron\CP\Mysql::get_by_product($mysql_product);

	if (count($databases) == 0) {
		echo "\t" . 'no databases' . "\n";
		continue;
	}

	foreach ($databases as $database) {
		$external_connections = 'false';
		if ($database->host != 'localhost') {
			$external_connections = 'true';
		}
		echo "\t" . 'Database: "' . $database->database_name . '", Username: "' . $database->username . '", External connections: ' . $external_connections . "\n";
	}

}
