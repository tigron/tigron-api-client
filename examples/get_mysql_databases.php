<?php
include 'credentials.php';

/**
 * Fetch all the product_types from Tigron Control panel
 * The product_types will be grouped based on their category
 */
$webhosting = Tigron\Cp\Product\Type\Category::get_by_identifier('webhosting');
$user = \Tigron\Cp\User::Get();

if ($user->is_reseller) {
	$reseller = \Tigron\Cp\Reseller::get_by_id($user->reseller_id);
	$users = \Tigron\Cp\User::get_by_reseller($reseller);
} else {
	$users = [ $user ];
}


foreach ($users as $user) {

	$products = Tigron\Cp\Product::get_by_user_category($user, $webhosting);

	if (count($products) == 0) {
		continue;
	}

	echo $user->username . "\n";

	foreach ($products as $product) {
		$product_type = \Tigron\Cp\Product\Type::get_by_id($product->product_type_id);
		echo "\t" . $product->domain . '.' . $product->tld . " (" . $product_type->name . ")\n";

		try {
			$mysql_product = $product->get_mysql_product();
		} catch (Exception $e) {
			echo "\t\t" . 'no databases' . "\n";
			continue;
		}

		$databases = \Tigron\Cp\Mysql::get_by_product($mysql_product);

		if (count($databases) == 0) {
			echo "\t\t" . 'no databases' . "\n";
			continue;
		}

		foreach ($databases as $database) {
			$external_connections = 'false';
			if ($database->host != 'localhost') {
				$external_connections = 'true';
			}
			echo "\t\t" . 'Database: "' . $database->database_name . '", Username: "' . $database->username . '", External connections: ' . $external_connections . "\n";
		}
	}
}
