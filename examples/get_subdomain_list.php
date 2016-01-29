<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

/**
 * Fetch all the domain names from Tigron Control panel and output
 * all information
 */
$category = Tigron\CP\Product\Type\Category::get_by_identifier('domain');

$user = \Tigron\CP\User::Get();

if ($user->is_reseller) {
	$reseller = \Tigron\CP\Reseller::get_by_id($user->reseller_id);
	$users = \Tigron\CP\User::get_by_reseller($reseller);
} else {
	$users = [ $user ];
}

echo 'user;domain;firstname;lastname;company;email' . "\n";

foreach ($users as $user) {
	$products = Tigron\CP\Product::get_by_user_category(Tigron\CP\User::get(), $category);

	foreach ($products as $product) {
		$subdomains = Tigron\CP\Subdomain::get_by_domain_tld($product->domain, $product->tld);

		foreach ($subdomains as $subdomain) {

			echo $user->username . ';';
			echo $product->domain . '.' . $product->tld . ';';
			echo $subdomain->name . '.' . $product->domain . '.' . $product->tld . ';';
			echo "\n";
		}
	}
}
