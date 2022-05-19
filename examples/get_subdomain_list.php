<?php
include 'credentials.php';

/**
 * Fetch all the domain names from Tigron Control panel and output
 * all information
 */
$category = Tigron\Cp\Product\Type\Category::get_by_identifier('domain');

$user = \Tigron\Cp\User::Get();

if ($user->is_reseller) {
	$reseller = \Tigron\Cp\Reseller::get_by_id($user->reseller_id);
	$users = \Tigron\Cp\User::get_by_reseller($reseller);
} else {
	$users = [ $user ];
}

echo 'user;domain;firstname;lastname;company;email' . "\n";

foreach ($users as $user) {
	$products = Tigron\Cp\Product::get_by_user_category(Tigron\Cp\User::get(), $category);

	foreach ($products as $product) {
		$subdomains = Tigron\Cp\Subdomain::get_by_domain_tld($product->domain, $product->tld);

		foreach ($subdomains as $subdomain) {

			echo $user->username . ';';
			echo $product->domain . '.' . $product->tld . ';';
			echo $subdomain->name . '.' . $product->domain . '.' . $product->tld . ';';
			echo "\n";
		}
	}
}
