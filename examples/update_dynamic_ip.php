<?php
include dirname(__FILE__) . '/../../../autoload.php';
include 'credentials.php';

/**
 * Name
 */
$name = 'dynamic';

/**
 * Domain
 */
$domain = 'mydomain';

/**
 * TLD
 */
$tld = 'be';


$subdomain = Tigron\CP\Subdomain::get_by_name_domain_tld($name, $domain, $tld);
$my_ip = file_get_contents('http://ipecho.net/plain');
$subdomain->target = $my_ip;
$subdomain->save();
